@extends('layouts.dashboard')
@section('content')

<div class="card">
	<div class="card-body">
		<p class="h1 text-center">{{ $title }} &nbsp <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#basicExampleModal"><i class="fa fa-users mr-1"></i>Share</button> </p>
		<!-- Button trigger modal -->

		<!-- Modal -->
		<div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<form action="{{ route('access.grant', $tnc_id) }}" method="POST">
					@csrf
						<div class="modal-header">
							<h6 class="modal-title" id="exampleModalLabel">Share with others</h6>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p>People</p>
							<select class="js-example-basic-multiple" style="width: 75%" name="users[]" multiple="multiple">
								@foreach($users as $user)
									<option value="{{ $user->id }}">{{ $user->email }}</option>
								@endforeach
							</select>
							<select class="js-example-basic-single" style="width: 15%" name="access">
								<option value="1">Can edit</option>
								<option value="2">Can read</option>
							</select>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="{{ config('etherpad.url') }}/p/{{ $padID }}" allowfullscreen></iframe>
		</div>
	</div>
</div>

@endsection

@section('script')
<script type="text/javascript">
	$(document).ready(function() {
    	$('.js-example-basic-multiple').select2();
    	$('.js-example-basic-single').select2();
	});
</script>
@endsection
