<template>
  <div>
    <mu-appbar color="primary" class="app-navbar is-open">
      <template slot="default">
        {{ $route.meta.title }}
      </template>
      <template slot="right">
        <mu-menu open-on-hover>
          <mu-button flat>
            <mu-icon value="account_circle" color="white" />
          </mu-button>
          <mu-list slot="content">
            <mu-sub-header>{{ username }}</mu-sub-header>
            <mu-list-item button>
              <mu-list-item-content>
                <mu-list-item-title>用户设置</mu-list-item-title>
              </mu-list-item-content>
            </mu-list-item>
            <mu-list-item button @click="logout">
              <mu-list-item-content>
                <mu-list-item-title>退出</mu-list-item-title>
              </mu-list-item-content>
            </mu-list-item>
          </mu-list>
        </mu-menu>
      </template>
    </mu-appbar>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: ''
    }
  },
  created() {
    this.username = this.$store.getters.userInfo.username
  },
  methods: {
    logout() {
      this.$store.dispatch('Logout').then(response => {
        location.reload()
      }).catch(() => {
        this.$store.dispatch('FedOut').then(response => {
          location.reload()
        })
      })
    }
  }
}
</script>

<style scoped>
    .app-navbar {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        z-index: 101;
        overflow: hidden;
    }
    .app-navbar.is-open {
        left: 256px;
    }

</style>
