<div class="container">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">
        <strong>@header</strong>
      </a>
    </div>
  </div>
</nav>


<div class="container">

		<div class="col-md-12">
			<strong style="font-size: 18px; color: red">[CreativeException]</strong><br/>
			@exception_title
		</div>
		
		<div class="col-md-12">
			@exception_message
			@calleds
		</div>
</div>

<style>
		.error_info{
			display: block;
			border: 1px solid #c2c2c2;
			background-color: #fff;
			padding: 15px;
			margin: 15px auto;
			white-space: nowrap;
			overflow:hidden
			
		}
		.error_info strong{
			text-transform: uppercase;
		}
</style>