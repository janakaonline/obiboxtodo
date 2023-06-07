<template>
  <main>
    <p>Hello there! {{this.message}}</p>
  </main>
</template>

<script>
import { mapState } from 'vuex'

export default {
  computed: mapState({
    message: state => state.overview.welcome
  }),
}
</script>