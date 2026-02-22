@extends('layouts.app-master')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/twitter-bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatable-bootstrap.css') }}" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Ledger Statement: {{ $store->name }} ({{ $store->code }})</h3>
                    <div class="text-end">
                        <div class="mb-2">
                            <a href="{{ route('ledger.export_pdf', $store->id) }}" class="btn btn-outline-danger btn-sm"><i
                                    class="bi bi-file-pdf"></i> PDF</a>
                            <a href="{{ route('ledger.export_excel', $store->id) }}"
                                class="btn btn-outline-success btn-sm"><i class="bi bi-file-excel"></i> Excel</a>
                        </div>
                        <h4 class="mb-0">Current Balance: <span
                                class="@if($balance > 0) text-danger @else text-success @endif">₹
                                {{ number_format($balance, 2) }}</span></h4>
                        <small class="text-muted">Positive = Payable (Debit), Negative = Advance (Credit)</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="ledger-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Ref No</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Debit (₹)</th>
                                    <th>Credit (₹)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payment Modal (Simplified) -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1">
        <!-- ... form to post to payment.store ... -->
    </div>

@endsection

@push('js')
    <script src="{{ asset('assets/js/other/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/other/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(function () {
            $('#ledger-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('ledger.show', $store->id) }}',
                columns: [
                    { data: 'txn_date', name: 'txn_date' },
                    { data: 'reference_no', name: 'reference_no', defaultContent: '-' },
                    { data: 'type', name: 'type' },
                    { data: 'notes', name: 'notes' },
                    {
                        data: 'amount',
                        name: 'debit',
                        render: function (data, type, row) {
                            return row.type.toLowerCase() === 'debit' ? data : '-';
                        }
                    },
                    {
                        data: 'amount',
                        name: 'credit',
                        render: function (data, type, row) {
                            return row.type.toLowerCase() === 'credit' ? data : '-';
                        }
                    },
                    {
                        data: 'id', name: 'action', render: function (data, type, row) {
                            return ''; // View Details button?
                        }
                    }
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush