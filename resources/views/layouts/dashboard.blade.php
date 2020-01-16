<html>
    @include('includes.head')
    <style type="text/css">    	
    	body{
    		background-color: #eeeeee;
    	}
    </style>
<body class="fixed-sn deep-purple-skin">
    @include('includes.header')
    <main>
		@yield('content')
	</main>
	@include('includes.footer')
	<script type="text/javascript">
		// SideNav Button Initialization
		$(".button-collapse").sideNav();
		// SideNav Scrollbar Initialization
		var sideNavScrollbar = document.querySelector('.custom-scrollbar');
		Ps.initialize(sideNavScrollbar);
	</script>
</body>
</html>
