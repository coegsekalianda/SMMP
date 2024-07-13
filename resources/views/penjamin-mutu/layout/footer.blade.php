</div>
</div>
<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Sistem Manajemen Mutu Perkuliahan</span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2024. All rights reserved.</span>
    </div>
</footer>
<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<!-- plugins:js -->
<script src="{{ asset('/assets/template/vendors/js/vendor.bundle.base.js')}}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('/assets/template/vendors/chart.js/Chart.min.js')}}"></script>
<script src="{{ asset('/assets/template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ asset('/assets/template/vendors/progressbar.js/progressbar.min.js')}}"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('/assets/template/js/off-canvas.js')}}"></script>
<script src="{{ asset('/assets/template/js/hoverable-collapse.js')}}"></script>
<script src="{{ asset('/assets/template/js/template.js')}}"></script>
<script src="{{ asset('/assets/template/js/settings.js')}}"></script>
<script src="{{ asset('/assets/template/js/todolist.js')}}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ asset('/assets/template/js/jquery.cookie.js')}}" type="text/javascript"></script>
<script src="{{ asset('/assets/template/js/dashboard.js')}}"></script>
<script src="{{ asset('/assets/template/js/Chart.roundedBarCharts.js')}}"></script>
<script src="{{asset('/node_modules/datatables/media/js/jquery.dataTables.min.js')}}"></script>

<!-- tinymce -->
<script src="https://cdn.tiny.cloud/1/5h0affhvvi6gh349o8nfwzsjq67tjij9h4fb61meqczi1xq9/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.tiny',
        plugins: [
            'autolink', 'lists', 'link', 'image', 'preview',
            'searchreplace', 'code', 'fullscreen',
            'insertdatetime', 'media', 'wordcount'
        ],
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar_mode: 'floating',
    });
</script>
<script>
    $(document).ready(function() {
        $('.dataTable').DataTable({
            "aaSorting": []
        });
    });
</script>
<!-- End custom js for this page-->
</body>

</html>