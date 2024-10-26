<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="../css/datepicker.css" rel="stylesheet" media="screen">
  <link href="../css/bootstrap.css" rel="stylesheet" media="screen">
  <link href="//getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.css" rel="stylesheet">
  <link href="//getbootstrap.com/2.3.2/assets/css/bootstrap-responsive.css" rel="stylesheet">

  <style>
    .hidden-input {
      position: absolute; /* ป้องกันไม่ให้ input มีผลต่อ layout */
      opacity: 0; /* ซ่อน input */
      width: 0; /* ขนาดเป็น 0 */
      height: 0; /* ขนาดเป็น 0 */
      pointer-events: none; /* ป้องกันการคลิก */
    }
  </style>

</head>

<body>
  <input class="input-medium hidden-input" type="text" data-provide="datepicker" data-date-language="th-th">

  <!-- Placed at the end of the document so the pages load faster -->
  <script src="//getbootstrap.com/2.3.2/assets/js/jquery.js"></script>
  <script src="//getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.js"></script>

  <script src="../js/bootstrap-datepicker.js"></script>
  <script src="../js/bootstrap-datepicker-thai.js"></script>
  <script src="../js/locales/bootstrap-datepicker.th.js"></script>

  <script id="example_script" type="text/javascript">
    function demo() {
      $('.datepicker').datepicker();
    }
  </script>

  <script type="text/javascript">
    $(function () {
      $('pre[data-source]').each(function () {
        var $this = $(this),
          $source = $($this.data('source'));

        var text = [];
        $source.each(function () {
          var $s = $(this);
          if ($s.attr('type') == 'text/javascript') {
            text.push($s.html().replace(/(\n)*/, ''));
          } else {
            text.push($s.clone().wrap('<div>').parent().html()
              .replace(/(\"(?=[[{]))/g, '\'')
              .replace(/\]\"/g, ']\'').replace(/\}\"/g, '\'') // javascript not support lookbehind
              .replace(/\&quot\;/g, '"'));
          }
        });

        $this.text(text.join('\n\n').replace(/\t/g, '    '));
      });

      prettyPrint();
      demo();
    });
  </script>

</body>

</html>