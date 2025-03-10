@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Dashboard</h1>
            
            <!-- API Endpoints Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="mb-0">Available API Endpoints</h2>
                </div>
                <div class="card-body">
                    <h3>Debit Cards</h3>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">GET /debit-cards - List all debit cards</li>
                        <li class="list-group-item">POST /debit-cards - Create a new debit card</li>
                        <li class="list-group-item">GET /debit-cards/{id} - Get debit card details</li>
                        <li class="list-group-item">PUT /debit-cards/{id} - Update debit card</li>
                        <li class="list-group-item">DELETE /debit-cards/{id} - Delete debit card</li>
                    </ul>

                    <h3>Transactions</h3>
                    <ul class="list-group">
                        <li class="list-group-item">GET /debit-card-transactions - List all transactions</li>
                        <li class="list-group-item">POST /debit-card-transactions - Create new transaction</li>
                        <li class="list-group-item">GET /debit-card-transactions/{id} - Get transaction details</li>
                    </ul>
                </div>
            </div>

            <!-- Debit Cards Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="mb-0">Your Debit Cards</h2>
                </div>
                <div class="card-body">
                    @if($debitCards->isEmpty())
                        <p>No debit cards found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Card Number</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($debitCards as $card)
                                    <tr>
                                        <td>{{ $card->card_number }}</td>
                                        <td>{{ $card->status }}</td>
                                        <td>
                                            <a href="{{ route('debit-cards.edit', $card->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="{{ route('debit-cards.transactions', $card->id) }}" class="btn btn-sm btn-info">Transactions</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Transactions Section -->
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Recent Transactions</h2>
                </div>
                <div class="card-body">
                    @if($transactions->isEmpty())
                        <p>No transactions found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->status }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection