@extends('pg::layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12 col-md-offset-12">
                <div class="mb-5 text-center">
                    <svg viewBox="0 0 24 24" height="100" width="100" aria-hidden="true" focusable="false" fill="currentColor" color="grey" class="StyledIconBase-sc-bdy9j4 jbPeIR"><path d="M11.953 2C6.465 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.493 2 11.953 2zM12 20c-4.411 0-8-3.589-8-8s3.567-8 7.953-8C16.391 4 20 7.589 20 12s-3.589 8-8 8z"></path><path d="M11 7h2v6h-2zm0 8h2v2h-2z"></path></svg>
                </div>
                <div class="alert alert-danger text-center" role="alert">
                    متاسفانه در این لحظه افزایش اعتبار امکان پذیر نیست!
                </div>
            </div>
        </div>
    </div>
@endsection
