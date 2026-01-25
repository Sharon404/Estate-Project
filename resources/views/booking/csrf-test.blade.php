@extends('frontend.layouts.app')

@section('title', 'CSRF Test')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 mb-3">CSRF Validation Test</h1>
                        <p class="text-muted mb-4">Submit this form to verify that CSRF tokens are working correctly.</p>

                        <form action="{{ route('csrf.test.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Sample Input</label>
                                <input type="text" name="sample" class="form-control" placeholder="Type anything" required>
                            </div>
                            <button type="submit" class="btn-main">Submit CSRF Test</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
