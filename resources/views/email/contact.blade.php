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
										<table class="center">
											<tr>
												<td colspan="2" style="text-align:left;">
													<h6 style="font-size: 20px; color: #14b2b6; font-weight: bold;margin-top:10px; margin-bottom: 20px;">Kontaktinformationen</h6>
												</td>
											</tr>
											
														@foreach($contact_data as $icon => $value)
														<tr>
																<td colspan="2" valign="bottom" style="vertical-align:baseline;">
																	<img src="http://www.swiss-magic-kunden.ch/html/click2energy/email/icon-{{ $icon }}.jpg" width="10" height="12" style="padding-top:2px;" alt="contact person"/>
																	<span style="padding-left:10px;">{{$value}}</span>
																</td>
														</tr>
														@endforeach
			
											<tr>
												<td colspan="2" height="10">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="2" height="1">
													<table style="border-top:1px solid #d0f0f0;" width="100%">
														<tr>
															<td height="1">&nbsp;</td>
														</tr>
													</table>
												</td>
											</tr>
											@if($desc)
											<tr>
												<td colspan="2">
													<h6 style="margin-top:0; margin-bottom: 0;font-size: 20px; color: #14b2b6; font-weight: bold;">Nachricht</h6>
												</td>
											</tr>
											<tr>
												<td colspan="2"><p style="color:#3b3b3a; font-family: 'Arial', sans-serif;  font-weight: normal; padding: 0; margin: 0;margin-bottom: 20px;">{{ $desc }}</p></td>
											</tr>
										@endif
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