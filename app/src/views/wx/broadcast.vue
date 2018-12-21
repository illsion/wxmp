<template>
  <mu-container>
    <mu-paper :z-depth="1" class="app-paper">
      <mu-row class="action-filter">
        <mu-col span="12">
          <mu-button color="info" small @click="openDialog">
            创建消息
          </mu-button>
        </mu-col>
      </mu-row>
      <mu-divider />
      <mu-data-table stripe :columns="columns" :data="list">
        <template slot-scope="scope">
          <td class="is-center">
            {{ scope.row.title }}
          </td>
        </template>
      </mu-data-table>
    </mu-paper>
    <mu-flex class="pagination-filter" justify-content="center">
      <mu-pagination raised :total="pageData.count" :page-size="pageData.limit" :current.sync="pageData.page" @change="fetchData" />
    </mu-flex>
    <update-broadcast :open.sync="update.open" />
  </mu-container>
</template>

<script>
import check from './check'
import UpdateBroadcast from './components/UpdateBroadcast'

export default {
  components: {
    UpdateBroadcast
  },
  data() {
    return {
      columns: [],
      list: [],
      pageData: {
        count: 0,
        limit: 10,
        page: 1
      },
      update: {
        open: true
      }
    }
  },
  created() {
    // check()
    this.fetchData()
  },
  methods: {
    fetchData() {
      // const queryData = {
      //   page: this.pageData.page
      // }
    },
    openDialog() {
      this.update.open = true
    }
  }
}
</script>

<style scoped>

</style>
