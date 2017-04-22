<?php
print_r($_POST['cb']);


 ?> 



<form class="form-horizontal" role="form" method="post" action="test2.php">
 <div class="checkbox">
      <label><input type="checkbox" value="a" name="cb[]">Option 1</label>
    </div>
    <div class="checkbox">
      <label><input type="checkbox" value="b" name="cb[]">Option 2</label>
    </div>
    <div class="checkbox">
      <label><input type="checkbox" value="c" name="cb[]">Option 3</label>
    </div>
    <button type="submit" class="btn btn-success">Submit</button>
    </form>
