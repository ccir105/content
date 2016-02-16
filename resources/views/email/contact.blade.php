@extends('email.layout')

@section('main-title')
	Kontaktanfrage
@stop

@section('content')

<table class="row" width="600px" align="center">
	<tr>
		<td class="wrapper last offset-by-two">
			<table class="seven columns" width="330px" align="center">
				<tr>
					<td class="center" align="center" style="border:1px solid #14b2b6; padding-left:20px; padding-right:20px; ">
						<center>
							<table class="" width="330px" align="center">
								<tr>
									<td>
										<table class="center" style="margin-top:-10px;">
											<tr>
												<td><h6 style="float:left; padding-left:10px; padding-right:10px; background:#fff; margin-left:-10px; font-size: 20px; color: #14b2b6; font-weight: bold;margin-top:0px; margin-bottom: 20px;">Kontaktinformationen</h6></td>
											</tr>
										</table>
										<table class="center">
											@foreach($contact_data as $name => $value)
											<tr>
												<td><strong>{{$name}}</strong></td>
												<td>{{$value}}</td>
											</tr>
											@endforeach
											<tr>
												<td colspan="2" height="20"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</center>
					</td>
					<td class="expander"></td>
				</tr>
				<tr>
					<td>
						<table class="center">
							<tr>
								<td height="40"></td>
							</tr>
						</table>
					</td>
					<td class="expander"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
@stop