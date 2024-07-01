<template>
    <div class="login-container">
        <div class="header">
            <div class="logo-container">
                <img src="/favicon.ico" alt="Logo" class="logo" />
                <span class="logo-text">DentHub</span>
            </div>
        </div>
        <div class="content">
            <h1 style="text-align: center">Conectează-te</h1>

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
                <button type="submit" class="btn btn-primary">
                    Conectează-te
                </button>
            </form>
            <div class="register-link">
                <p>
                    Nu ai un cont?
                    <a href="#">Înregistrează-te aici</a>.
                </p>
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
html,
body {
    height: 100%;
    margin: 0;
    background-color: #efefef;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-container {
    max-width: 700px;
    width: 500px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.header {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-bottom: 2rem; /* Adjust this to increase space below the header */
}

.logo-container {
    display: flex;
    align-items: center;
}

.logo {
    width: 50px;
    height: 50px;
    margin-right: 10px; /* Add space between the logo image and the text */
}

.logo-text {
    font-size: 2rem;
    font-weight: bold;
}

.content {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.btn {
    width: 100%;
}

.register-link {
    margin-top: 1rem; /* Adjust this to control the space above the register link */
    text-align: center;
}

.register-link a {
    color: #007bff;
    text-decoration: none;
}

.register-link a:hover {
    text-decoration: underline;
}
</style>
