<?php
/*
 * Searchable local php doc entrance
 *
 * @author Robin ZHAO (boborabit@gmail.com)
 * @version 1.0
 * @date 2013-02-05
 *
 */
?>

<html>
  <head>
    <title>Searchable PHP-DOC</title>
    <script src="jquery-1.9.1.min.js" language="javascript"></script>
    <script language="javascript">
      $(document).ready(function() {
        $("#bar").keypress(function(event) {//Instant search

          if (event.which == 13) {//Enter pressed.
            event.preventDefault();
            var firstA = $("#result  a:first");
            renderPage(firstA.html());
          } else {

            if($(this).val().length < 3) {
              return;
            }
           
            $.get('request.php?key=' + $(this).val() + '&type='
                    + $('#type').val(), function(data) {
              $('#result').html(renderLinks(data));
            }, 'json');
          }
        });
      });

      function renderLinks(data) {
        var str = '';
        for (var i = 0; i < data.length; i++) {
          str += '<a href="javascript:renderPage(\'' + data[i] + '\')">' + data[i] + '</a><br />';
        }
        return str;
      }
      function renderPage(link) {
        console.log(link);
        $('#doc iframe').attr('src', 'html/' + link);
      }
    </script>
  </head>
  <body >
    <?php
    $folder = "html";
    $file = "index.html";
    if (isset($_GET['file']) && !empty($_GET['file'])) {
      $file = $_GET['file'];
    }
    ?>
    <div id="doc" style="width:810px;float:left">
      <iframe src="<?php echo $folder . '/' . $file; ?>" width="800" height="900">

      </iframe>
    </div>
    <div style="float:left;width:300px">
      <div id="search">
        <form>
          <input type="text" name="bar" id="bar"/>
          <select name="type" id="type">
            <option value="1" selected="selected">function name</option>
            <option value="2" >file name</option>
          </select>
        </form> 
      </div>
      <div id="result"></div>
    </div>
  </body>
</html>
