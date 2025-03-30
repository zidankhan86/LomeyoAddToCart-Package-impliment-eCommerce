@extends('backend.layout.app')
@section('content')

@extends('backend.layout.app')
@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Order Details</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->email }}</td>
                            <td>${{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="badge {{ $order->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                {{-- <a href="{{ route('order.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('order.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('order.destroy', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


@endsection
