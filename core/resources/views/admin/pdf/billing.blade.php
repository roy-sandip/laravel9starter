<table style="width:100%" border="0">
	<tr>
		<td>
			Bill Date: {{$invoice->created_at->format('d M, Y')}} <br>
			Bill Period: {{$invoice->period()}}

		</td>
		<td style="text-align:right;">
			<b>Billed To,</b><br>
			{{$invoice->agent->name}} <br>
			<b>{{$invoice->agent->company}}</b> <br>
			<b>{{$invoice->agent->phone}}</b>

		</td>
	</tr>

</table>

<br><br>

<table style="width:100%;font-size:10px;text-align: center;" border="1" cellpadding="3">
	<thead>
		<tr style="background-color:#ddd;width: 100%;">
			<th style="width:30px;text-align: center;">SL</th>
			<th>AWB</th>
			<th>Date</th>
			<th style="width:150px;">Receiver</th>
			<th style="width:125px;">Dest.</th>
			<th style="width:45px;">Weight</th>
			<th>Service</th>
			<th style="width:50px;">Bill</th>
			<th style="width:50px;">Paid</th>
			<th style="width:100px;">Remark</th>
		</tr>
	</thead>
	<tbody>
		@foreach($invoice->billings as $item)
		<tr  nobr="true">
			<td style="width:30px;text-align: center;">{{$loop->iteration}}</td>
			<td>{{$item->shipment->awb}}</td>
			<td>{{$item->shipment->booking_date->format('d M, Y')}}</td>
			<td style="width:150px;">{{$item->shipment->receiver->name}}</td>
			<td style="width:125px;">{{$item->shipment->receiver->country}}</td>
			<td style="width:45px;">{{$item->shipment->weight}}</td>
			<td>{{$item->shipment->service->name}}</td>
			<td style="width:50px;text-align: right;">{{$item->getBill()}}</td>
			<td style="width:50px;text-align: right;">{{$item->getPaid()}}</td>
			<td style="width:100px;">{{$item->comment}}</td>
		</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr style="text-align:right;background-color: #28282B;color: #fff;font-weight: bold;">
			<td colspan="7">Total</td>
			<td>{{$invoice->getBill()}}</td>
			<td>{{$invoice->getPaid()}}</td>
			<td></td>
		</tr>
		<tr style="text-align:right;">
			<td colspan="7">Paid (-)</td>
			<td>{{$invoice->getPaid()}}</td>
			<td>-</td>
			<td></td>
		</tr>

		@if($invoice->lastInvoice->id)
		<tr style="text-align:right;">
			<td colspan="3">{{$invoice->lastInvoice->created_at->format('d M, Y')}}</td>
			<td colspan="4">Last Statement ({{$invoice->lastInvoice->id}})</td>
			<td>{{$invoice->lastInvoice->balance}}</td>
			<td>-</td>
			<td></td>
		</tr>
		@endif

		@foreach($invoice->transactions as $item)
		<tr style="text-align:right;">
			<td colspan="3">{{$item->datetime}}</td>
			<td colspan="4">
				{{$item->comment}}
				(@if($item->type == 'c') + @else - @endif)
			</td>
			<td colspan="1">
				{{$item->amount}}
			</td>
			<td></td>
			<td></td>
		</tr>
		@endforeach
		<tr style="text-align:right;background-color: #28282B;color: #fff;font-weight: bold;">
			<td colspan="7">Closing Balance</td>
			<td>{{$invoice->getBalance()}}</td>
			<td></td>
			<td></td>
		</tr>
	</tfoot>
</table>