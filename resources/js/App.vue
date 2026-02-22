<template>
    <router-view v-if="!auth.loading" />
</template>

<script>
import { authState } from './auth'

export default {
    setup() {
        return {
            auth: authState
        }
    }
}
</script>