<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<html>
<body class="sb-nav-fixed">
    <style>
        /* Style all input fields */
        input {
          width: 100%;
          padding: 12px;
          border: 1px solid #ccc;
          border-radius: 4px;
          box-sizing: border-box;
          margin-top: 6px;
          margin-bottom: 16px;
        }

        /* Style the submit button */
        input[type=submit] {
          background-color: #04AA6D;
          color: white;
        }

        /* Style the container for inputs */
        .container {
          background-color: #f1f1f1;
          padding: 20px;
          width: 100vw;
        }

        /* The message box is shown when the user clicks on the password field */
        #message {
          display:none;
          background: #f1f1f1;
          color: #000;
          position: relative;
          padding: 20px;
          margin-top: 10px;
        }

        #message p {
          padding: 10px 35px;
          font-size: 18px;
        }

        /* Add a green text color and a checkmark when the requirements are right */
        .valid {
          color: green;
        }

        .valid:before {
          position: relative;
          left: -35px;
          content: "✔";
        }

        /* Add a red text color and an "x" when the requirements are wrong */
        .invalid {
          color: red;
        }

        .invalid:before {
          position: relative;
          left: -35px;
          content: "✖";
        }
        .eye {
            margin-bottom: -2px;
            margin-left: 10px;
        }
    </style>
    <?php echo $__env->make('admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="layoutSidenav">
        <?php echo $__env->make('admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="layoutSidenav_content">
            <div class="container-fluid px-4">
                <div class="title d-flex flex-row justify-content-between">
                    <h1 class="mt-4">Хэрэглэгчийн мэдээлэл засах</h1>
                </div>
                <div class="card mb-4 container">
                    <div class="card-body">
                        
                        <form method="POST" action="<?php echo e(route('user.profile.edit')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="username">Нэвтрэх нэр</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo e($admin->username); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Утас</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e($admin->phone); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Мэйл</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo e($admin->email); ?>" required>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="password">Нууц үг</label>
                                    <span class="eye" onclick="togglePasswordVisibility()">
                                        <i id="eye-icon" class="fa fa-eye mt-1 ml-2"></i>
                                    </span>
                                </div>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" value="<?php echo e($admin->password); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Нууц үг давтах</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" value="Submit">Шинэчилэх</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(Session::has('message') && Session::get('message') == 'successEditProfile'): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Амжилттай!',
                text: 'Мэдээлэл амжилттай шинэчлэгдлээ!',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo e(route('admin.posts')); ?>";
                }
            });
        </script>
    <?php elseif(Session::has('message') && Session::get('message') == 'errorEditProfile'): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Алдаа!',
                text: 'Шинэчлэх явцад алдаа гарлаа.',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById("password");
            const confirmPasswordField = document.getElementById("password_confirmation");
            const eyeIcon = document.getElementById("eye-icon");
            const eyeIconConfirm = document.getElementById("eye-icon-confirm");

            if (passwordField.type === "password" || confirmPasswordField.type === "password") {
                passwordField.type = "text";
                confirmPasswordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
                eyeIconConfirm.classList.remove("fa-eye");
                eyeIconConfirm.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                confirmPasswordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
                eyeIconConfirm.classList.remove("fa-eye-slash");
                eyeIconConfirm.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/user/userProfile.blade.php ENDPATH**/ ?>