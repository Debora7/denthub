<template>
    <div
        class="d-flex justify-content-center align-items-center vh-100 bg-light"
    >
        <div class="login-container">
            <div class="header text-center mb-4">
                <div
                    class="logo-container d-flex align-items-center justify-content-center"
                >
                    <img src="/favicon.ico" alt="Logo" class="logo me-2" />
                    <span class="logo-text h1 fw-bold">DentHub</span>
                </div>
            </div>
            <div class="content">
                <h1 class="text-center">Conectează-te</h1>
                <form @submit.prevent="login" class="form-container">
                    <div class="input-group mb-3 w-100">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Email"
                            v-model="email"
                        />
                    </div>
                    <div class="input-group mb-3">
                        <input
                            type="password"
                            class="form-control"
                            placeholder="Parolă"
                            v-model="password"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        Conectează-te
                    </button>
                </form>
                <div class="register-link mt-3 text-center">
                    <p>
                        Nu ai un cont?
                        <a href="#" class="text-primary"
                            >Înregistrează-te aici</a
                        >.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
        };
    },
    methods: {
        login() {
            // Handle login logic here
        },
    },
};
</script>

<style>
.login-container {
    max-width: 700px;
    width: 500px;
    padding: 1rem;
}

.logo {
    width: 100px;
    height: 100px;
}

.logo-text {
    font-size: 2rem;
    font-weight: bold;
}

.form-container {
    width: 100%;
}

.register-link a:hover {
    text-decoration: none;
}
</style>
