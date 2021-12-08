<h1>Register</h1>

<form method="POST" action="/register">
  <div class="row">
    <div class="col">
      <div class="mb-3">
        <label class="form-label">First Name</label>
        <input type="text" class="form-control" name="firstname">
      </div>
    </div>

    <div class="col">
      <div class="mb-3">
        <label class="form-label">Last Name</label>
        <input type="text" class="form-control" name="lastname">
      </div>
    </div>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" class="form-control" name="email">
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" class="form-control" name="password">
  </div>
  <div class="mb-3">
    <label class="form-label">Confirm Password</label>
    <input type="password" class="form-control" name="confirmPassword">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
