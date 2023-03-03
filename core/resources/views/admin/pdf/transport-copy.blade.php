@include('admin.pdf.header', ['logo' => 'abc'])
<table class="main_table border center">

<tr class="t_header">

<td>Origin</td><td>Reference</td><td>Pieces</td>

<td>Service</td>
<td>Destination</td>
<td>Weight</td>

</tr>

<tr >

<td>{{$shipment->shipper->country}}</td>

<td></td>

<td>1</td>

<td>{{$shipment->service->name}}</td>

<td>{{$shipment->receiver->country}}</td>

<td>{{$shipment->weight}}</td>

</tr>

</table>

<table class="border left">
	<tr>
		<td>SHIPPER: <b>{{$shipment->shipper->name}}</b></td>
		<td>Receiver: <b>{{$shipment->receiver->name}}</b></td>
	</tr>
</table>



<table class="border left address">
	<tr>
		<td>
			<span style="background-color:#000;color:#fff"> ADDRESS: </span> 
			{!! $shipment->shipper->printStreetLines() !!}
		</td>
		<td>
			<span style="background-color:#000;color:#fff"> ADDRESS: </span> 
			{!! $shipment->receiver->printStreetLines() !!}
		</td>
	</tr>
</table>



<table class="border left">
	<tr>
		<td colspan="2">CITY: {{$shipment->shipper->city}}</td>
		<td>ZIP: {{$shipment->shipper->zip}}</td>
		<td colspan="2">CITY: {{$shipment->receiver->city}}</td>
		<td>ZIP: {{$shipment->receiver->zip}}</td>
		
	</tr>

	<tr>
		<td  colspan="3">COUNTRY:  {{$shipment->shipper->country}}</td>
		<td colspan="4">COUNTRY: <span class="small">{{$shipment->receiver->country}}</span> </td>
	</tr>
</table>

<table class="border left">
	<tr>
		<td>PHONE: {{$shipment->shipper->phone}}</td>
		<td>PHONE: <b>{{$shipment->receiver->phone}}</b></td>
	</tr>
</table>

<table class="border left">
</table>



<table class="border left">
	<tr>

	<td>
		<span class="p">
			<span style="font-size:10px;font-weight:bold">DESCRIPTION OF GOODS:</span> 
			{{$shipment->description}}
		</span>
	</td>



		<td>
			<table class="left">
				<tr>
					<td>Picked up by: <i>antony</i></td>
				</tr>

			<tr>
				<td>Date-Time: <b>{{$shipment->date->format('d M, Y')}}</b>
				</td>
			</tr>
			</table>
		</td>
	</tr>
</table>







<style>	
.header {border-top:1px solid #000;border-right:1px solid #000;border-left:1px solid #000;padding:2px;padding-bottom:0px;margin-bottom:0px;}
.t_header, .t_header td, .awb_header, .awb_header td{background-color:#cccccc;font-weight:bold }

.author {font-size:10px; color:#333;text-align:left;font-weight:normal; opacity:0.50;}

.author tr td {text-align:right};

.p {font-size:10px; }

.left tr td {text-align:left;}

.left {padding:4px 5px; }

table, .awb_table {

    border-collapse: collapse;background-color:#fff;

}

.border td  {border: 1px solid black;text-align:center;}





.main_table {font-size:10px; padding:3px 0px;}

td {text-transform: capitalized; font-weight:normal;font-size: 12px}



	

.awb , .awb tr, .awb td {

    border: 1px solid black;

	text-align:center;

}

.header { }

.logo{color:#7F7F7F;display:inline-block;}

.address {display:inline-block; color:#000; font-size:12px;text-align:center;}

table {margin-bottom:1px;padding-bottom:0px;}

.logo {width:150px;height: 50px;}

.text-logo{font-weight:bold;font-size:25px;}
</style>
