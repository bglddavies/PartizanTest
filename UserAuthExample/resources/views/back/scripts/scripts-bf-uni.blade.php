<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/fwork.js"></script>
<script>
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        jqXHR.setRequestHeader('X-CSRF-Token', '{{{csrf_token()}}}');
    });
</script>