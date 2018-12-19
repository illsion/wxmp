<template>
  <mu-container>
    <mu-paper :z-depth="1" class="app-paper">
      <mu-sub-header>服务器信息</mu-sub-header>
      <mu-row gutter>
        <mu-col v-for="(item, key) in info" :key="key" span="6">
          <mu-list>
            <mu-list-item>
              <mu-list-item-content>
                <mu-list-item-title>{{ item.label }}</mu-list-item-title>
                <mu-list-item-sub-title>{{ item.value }}</mu-list-item-sub-title>
              </mu-list-item-content>
            </mu-list-item>
          </mu-list>
        </mu-col>
      </mu-row>
    </mu-paper>
  </mu-container>
</template>

<script>
import { getServerInfo } from '@/api/common'

export default {
  data() {
    return {
      info: []
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const serveInfo = this.$store.getters.serveInfo
      if (serveInfo.length === 0) {
        getServerInfo().then(response => {
          this.info = response
          this.$store.commit('SET_SERVEINFO', response)
        })
      } else {
        this.info = serveInfo
      }
    }
  }
}
</script>

<style scoped>

</style>
