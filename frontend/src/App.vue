<template>
    <header>
        <div class="bg-grey-darken-4 d-flex justify-center flex-column pa-2 mb-3">
            <img src="./assets/logo.svg" alt="" class="logo">
            <h1>To Do List</h1>
        </div>

        <nav class="d-flex justify-center">
            <v-btn @click="$router.push('/')" class="bg-indigo ma-2"> List</v-btn>
            <v-btn @click="$router.push('/overview')" class="bg-indigo ma-2"> Overview</v-btn>
        </nav>
    </header>

    <v-container>
        <RouterView/>
    </v-container>
</template>

<script setup>
import {RouterLink, RouterView} from "vue-router";
</script>

<style lang="sass">
@import "assets/main"

</style>
