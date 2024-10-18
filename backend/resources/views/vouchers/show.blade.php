@extends('master')

@section('content')
    <h1>Voucher Details</h1>

    <p><strong>Code:</strong> {{ $voucher->code }}</p>
    <p><strong>Type:</strong> {{ $voucher->type == 0 ? 'Fixed Value' : 'Percentage' }}</p>
    <p><strong>Discount Value:</strong> {{ $voucher->discount_value }}</p>
    <p><strong>Description:</strong> {{ $voucher->description ?? 'N/A' }}</p>
    <p><strong>Minimum Discount:</strong> {{ $voucher->discount_min }}</p>
    <p><strong>Maximum Discount:</strong> {{ $voucher->max_discount }}</p>
    <p><strong>Minimum Order Count:</strong> {{ $voucher->min_order_count }}</p>
    <p><strong>Maximum Order Count:</strong> {{ $voucher->max_order_count }}</p>
    <p><strong>Quantity:</strong> {{ $voucher->quantity }}</p>
    <p><strong>Used Times:</strong> {{ $voucher->used_times }}</p>
    <p><strong>Start Date:</strong> {{ $voucher->start_day ? \Carbon\Carbon::parse($voucher->start_day)->format('Y-m-d H:i') : 'N/A' }}</p>
<p><strong>End Date:</strong> {{ $voucher->end_day ? \Carbon\Carbon::parse($voucher->end_day)->format('Y-m-d H:i') : 'N/A' }}</p>
