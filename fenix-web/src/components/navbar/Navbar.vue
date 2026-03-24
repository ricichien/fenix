<template>
    <nav class="navbar-wrapper">
        <div class="navbar-container">
            <div class="nav-brand">
                Fênix<span>Edu</span>
            </div>

            <div class="nav-links">
                <a href="#" class="active">Home</a>
                <a href="#">Recursos</a>
                <a href="#">Instituições</a>
                <a href="#">Preços</a>
            </div>

            <div class="nav-actions">
                <button class="btn-text">Entrar</button>
            </div>
        </div>
    </nav>
</template>

<style scoped>
.navbar-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    z-index: 1000;
    padding: 20px 24px;
}

.navbar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    max-width: 1100px;
    height: 50px;
    background: transparent;
    padding: 0 10px;
}

.nav-brand {
    font-weight: 700;
    font-size: 18px;
    color: #ffffff;
    letter-spacing: -0.5px;
}

.nav-brand span {
    font-weight: 400;
    opacity: 0.7;
}

.nav-links {
    display: flex;
    gap: 40px;
}

.nav-links a {
    text-decoration: none;
    color: rgba(255, 255, 255, 0.7);
    font-size: 13px;
    font-weight: 500;
    transition: 0.3s;
}

.nav-links a:hover,
.nav-links a.active {
    color: #ffffff;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.btn-text {
    background: transparent;
    border: none;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    opacity: 0.8;
    transition: 0.3s;
}

.btn-text:hover {
    opacity: 1;
}

.btn-outline {
    background: transparent;
    border: none;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.btn-outline:hover {
    background: #ffffff;
    color: #ea580c;
}

@media (max-width: 768px) {
    .nav-links {
        display: none;
    }
}
</style>