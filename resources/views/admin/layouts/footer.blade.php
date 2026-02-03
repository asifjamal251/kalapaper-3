<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                {{get_app_setting('created_at')->format('Y')}} © {{get_app_setting('title')}}.
                <a href="{{route('admin.clear.cache')}}">Clear Cache</a>
            </div>

            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Design & Develop by <a href="https://artechnology.in">AR Technology</a>
                </div>
            </div>
        </div>
    </div>
</footer>