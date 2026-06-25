<div class="login-shell py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="login-card rounded-4 shadow-sm p-4 p-md-5 text-center">
                    <img src="/Vet_Check/logo.png" alt="VetCheck" class="app-logo-login mb-4 mx-auto d-block">
                    <h1 class="h4 fw-semibold mb-2">Connexion VetCheck</h1>
                    <p class="text-body-secondary mb-4">Accédez à votre espace de travail clinique.</p>

                    <form method="post" action="/Vet_Check/public/login" class="text-start">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
