
  @if (session()->has("message"))
  <script>
     	Toast.fire({
        icon: "info",
        title: "{{session("message")}}"
      })
</script>
@endif

