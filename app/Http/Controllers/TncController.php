<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Access;
use App\Tnc;
use Auth;
use DataTables;

class TncController extends Controller
{

    public function create(Request $request)
    {
        $vaidatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            ]);
        $data = $request->all();
        $tnc = Tnc::create([
            'title' => $data['title'],
            'user_id' => Auth::id(),
        ]);

        $etherpad = new \EtherpadLite\Client(config('etherpad.api_key'), config('etherpad.url'));
        $authorID = $etherpad->createAuthorIfNotExistsFor(Auth::id(), Auth()->user()->name)->getData('authorID');
        $groupID = $etherpad->createGroupIfNotExistsFor($tnc->id)->getData('groupID');
        $etherpad->createGroupPad($groupID, 'node');
        $padID = $groupID . '$node';
        $readOnlyID = $etherpad->getReadOnlyID($padID)->getData('readOnlyID');
        $sessionTime = time() + 24 * 60 * 60;
        $sessionID = $etherpad->createSession($groupID, $authorID, $sessionTime)->getData('sessionID');
        $host = parse_url(config('etherpad.url'), PHP_URL_HOST);
        setcookie('sessionID', $sessionID, $sessionTime, '/', $host);

        $tnc->pad_id = $padID;
        $tnc->pad_read_id = $readOnlyID;
        $tnc->save();

        Access::create([
            'tnc_id' => $tnc->id,
            'user_id' => Auth::id(),
            'access' => 3,
        ]);

        return redirect('tnc/'.$tnc->id);
    }

    public function show($id)
    {
        $access = Access::select('access')->where('user_id', Auth::id())->where('tnc_id', $id)->first()->access;
        $tnc = Tnc::find($id);
        $padID = $tnc->pad_id;
        if ($access == 2 || $access == 3) {
            $etherpad = new \EtherpadLite\Client(config('etherpad.api_key'), config('etherpad.url'));
            $authorID = $etherpad->createAuthorIfNotExistsFor(Auth::id(), Auth()->user()->name)->getData('authorID');
            $groupID = $etherpad->createGroupIfNotExistsFor($id)->getData('groupID');
            $sessionTime = time() + 24 * 60 * 60;
            $sessionID = $etherpad->createSession($groupID, $authorID, $sessionTime)->getData('sessionID');
            $host = parse_url(config('etherpad.url'), PHP_URL_HOST);
            setcookie('sessionID', $sessionID, $sessionTime, '/', $host);
        } else {
            $padID = $tnc->pad_read_id;
        }
        return view('tnc_show', [
            'access' => $access,
            'padID' => $padID,
            'title' => $tnc->title,
        ]);
    }

    public function listTncs()
    {
        return Datatables::of(Tnc::query())
            ->editColumn('title', function (Tnc $t) {
                return '<a href="'.url('tnc/'.$t->id).'" target="_blank"><u>'.$t->title.'</u><a>';
            })
            ->editColumn('created_at', function (Tnc $t) {
                return $t->created_at->diffForHumans();
            })
            ->addColumn('action', function (Tnc $t) {
                return '<a href="'.route('users.edit', $t->id).'" target="_blank" class="d-inline btn btn-primary">
            	<i class="fas fa-pencil-alt mr-1"></i> Edit Title</a> &nbsp;
            	<form action="'.route('users.destroy', $t->id).'" method="POST" class="d-inline-block">
                	'.csrf_field().'
                	<input type="hidden" name="_method" value="DELETE">
                	<button class="btn btn-danger"><i class="fas fa-trash-alt mr-1"></i> Delete</button>
            	</form>';
            })
            ->rawColumns([
                'title',
                'action'
            ])
            ->make(true);
    }
}
