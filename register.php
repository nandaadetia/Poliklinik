<?php
// register
try {
    if (isset($_POST['daftar'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        // tambah ke db
        if ($password == $password2) {
            $hash_password = md5($password);
            $query = mysqli_query($mysqli, "INSERT INTO user SET username='$username', password='$hash_password'");
            // cocokin dengan db, mencari datanya ada atau ga
            $cekdatabase = mysqli_query($mysqli, "SELECT * FROM user where username='$username' and password='$hash_password'");
            //fetch data
            $data = mysqli_fetch_array($cekdatabase);
            if ($query) {
                $_SESSION['log'] = 'True';
                $_SESSION['user_id'] = $data['id'];
                header('location:index.php?success=true');
            } else {
                header('index.php?page=register');
            }
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Password tidak sama!",
                        timer: 2000
                    })
                </script>';
        }
    };

    if (!isset($_SESSION['log'])) {
    } else {
        header('index.php?page=register');
    }
} catch (\Throwable $th) {
    echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Username sudah dipakai!",
                        timer: 2000
                    })
                </script>';
}
?>

<div class="container mt-5">
    <form method="post">
        <div class="mb-3">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Masukan username" required>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukan password" required>
        </div>
        <div class="mb-3">
            <label for="password2">Verifikasi Password</label>
            <input type="password" name="password2" id="password2" class="form-control" placeholder="Verifikasi password" required>
        </div>
        <input type="submit" name="daftar" class="btn btn-primary btn-lg btn-block w-100">
        <p class="mt-3 text-secondary text-center">Sudah Punya Akun? <a href="index.php?page=login" class="text-primary">Masuk</a></p>
    </form>
</div>