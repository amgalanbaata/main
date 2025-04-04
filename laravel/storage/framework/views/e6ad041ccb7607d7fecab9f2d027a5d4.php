<html>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin.css']); ?>
<style>
    body {
        background-image: url('<?php echo e(asset('images/monument-6623161_1280.jpg')); ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .bg-container::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: -1;
    }
</style>
<body class="bg-container bg-primary">
  <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
          <main>
              <div class="container">
                  <div class="row justify-content-center">
                      <div class="col-lg-5">
                          <div class="card shadow-lg border-0 rounded-lg mt-5">
                              <div class="card-header">
                                <img src="<?php echo e(asset('images/NBOG-logo.png')); ?>" width="300" alt="">
                            </div>
                              <div class="card-body">
                                  <form action="admin" method="POST">
                                      <?php echo csrf_field(); ?>
                                      <div class="form-floating mb-3">
                                          <input class="form-control" name="username" type="text" placeholder="Нэвтрэх нэр" onkeypress="document.getElementById('valid').innerHTML = '';" />
                                          <label for="inputUsername">Нэвтрэх нэр</label>
                                      </div>
                                      <div class="form-floating mb-3">
                                          <input class="form-control" name="password" type="password" placeholder="Нууц үг" onkeypress="document.getElementById('valid').innerHTML = '';" />
                                          <label for="inputPassword">Нууц үг</label>
                                      </div>
                                      <p style="color: red;font-size: 15px;" id="valid"><?php echo e($message); ?></p>
                                      <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                          <!-- <a class="small" href="password.html">Forgot Password?</a> -->
                                          <p></p>
                                          <button class="btn btn-primary" type="submit">Нэвтрэх</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </main>
      </div>
  </div>
</body>
</html>
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/login.blade.php ENDPATH**/ ?>