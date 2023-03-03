<table>
	<tr>
		<td>DHL Bill # {{$bill->bill_no}}</td>
	</tr>
	<tr>
		<td>Bill Date # {{$bill->bill_date->format('d M, Y')}}</td>
	</tr>
	<tr>
		<td style="text-align:center;">
			<br> <br>
			<u><b>Biling Statement</b></u> 
			<br>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td colspan="6">
						<table style="" border="1" cellpadding="5">
							<tr>
								<td colspan="1">Total Bill</td>
								<td colspan="3" style="text-align:right;">{{$bill->getBill(2)}}</td>
							</tr>
							<tr>
								<td colspan="1">Transactions</td>
								<td colspan="3">
									<table>
										<tr style="background-color:#ddd;">
											<td>Date</td>
											<td style="text-align:right;">Amount</td>
											<td></td>
										</tr>
										@foreach($bill->transactions as $item)
										<tr>
											<td>{{$item->datetime->format('d/m/Y')}}</td>
											<td style="text-align:right;">{{$item->getAmount(2)}}</td>
											<td></td>
										</tr>
										@endforeach
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="1">Total Paid</td>
								<td colspan="3" style="text-align:right;">{{$bill->getPaid(2)}}</td>
							</tr>
							<tr style="background-color:#ccc;font-weight: bold;">
								<td colspan="1">Balance</td>
								<td colspan="3" style="text-align:right;">{{$bill->getBalance(2)}}</td>
							</tr>
						</table>
					</td>					
				</tr>
			</table>
		</td>
	</tr>
</table>



