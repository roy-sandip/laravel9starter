


<table>
	<tr>
		<td><h1>Manifest # {{$manifest->id}}</h1></td>
		<td style="text-align:right;">
			Date: {{$manifest->date->format('d M, Y')}} <br>
			Total Items: {{$manifest->items->count()}} 
		</td>
	</tr>
</table>
<hr> <hr>
<table border="1" align="center">
	<thead>
		<tr style="background-color:#ccc;color: #000;">
			<th colspan="1">SL</th>
			<th colspan="2">AWB</th>
			<th colspan="6">Shipper</th>
			<th colspan="6">Receiver</th>
			<th colspan="4">Destination</th>
			<th colspan="4">Items</th>
			<th colspan="2">Weight</th>
			<th colspan="2">Service</th>
			<th colspan="2">Remark</th>
		</tr>
	</thead>
	<tbody align="left">
		@foreach($manifest->items as  $item)
		<tr nobr="true">
			<td colspan="1" >{{$loop->iteration}}</td>
			<td colspan="2">{{$item->shipment->awb}}</td>
			<td colspan="6">{{$item->shipment->shipper->name}}</td>
			<td colspan="6">{{$item->shipment->receiver->name}}</td>
			<td colspan="4">{{$item->shipment->receiver->country}}</td>
			<td colspan="4">{{$item->description}}</td>
			<td colspan="2">{{$item->shipment->weight}}</td>
			<td colspan="2">{{$item->shipment->service->name}}</td>
			<td colspan="2">{{$item->remark}}</td>
		</tr>
		@endforeach
	</tbody>
</table>