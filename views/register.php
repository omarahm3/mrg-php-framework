<h1>Register</h1>

<?php $form = \app\core\form\Form::begin('', 'post') ?>
<div class="row">
    <div class="col">
      <?php
      echo $form
        ->field($model, 'firstname')
        ->mainClass('mb-3')
        ->labelClass('form-labell')
        ->labelContent('First Name')
        ->inputClass('form-control')
        ->inputName('firstname')
      ?>
    </div>
    <div class="col">
      <?php
      echo $form
        ->field($model, 'lastname')
        ->mainClass('mb-3')
        ->labelClass('form-labell')
        ->labelContent('Last Name')
        ->inputClass('form-control')
        ->inputName('lastname')
      ?>
    </div>
</div>
<?php
echo $form
  ->field($model, 'email')
  ->mainClass('mb-3')
  ->labelClass('form-labell')
  ->labelContent('Email')
  ->inputClass('form-control')
  ->email()
  ->inputName('email')
?>
<?php
echo $form
  ->field($model, 'password')
  ->mainClass('mb-3')
  ->labelClass('form-labell')
  ->labelContent('Password')
  ->inputClass('form-control')
  ->password()
  ->inputName('password')
?>
<?php
echo $form
  ->field($model, 'confirmPassword')
  ->mainClass('mb-3')
  ->labelClass('form-labell')
  ->labelContent('Confirm Password')
  ->inputClass('form-control')
  ->password()
  ->inputName('confirmPassword')
?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php \app\core\form\Form::end() ?>