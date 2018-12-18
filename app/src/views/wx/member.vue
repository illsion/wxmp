<template>
  <mu-container>
    <mu-paper :z-depth="1" class="app-paper">
      <mu-row class="action-filter">
        <mu-col span="12">
          <mu-button color="info" small @click="synchronize">
            同步用户
          </mu-button>
        </mu-col>
      </mu-row>
      <mu-divider />
      <mu-data-table stripe :columns="columns" :data="list">
        <template slot-scope="scope">
          <td class="is-center">
            {{ scope.row.subscribe | formatStatus }}
          </td>
          <td class="is-center">
            <img :src="scope.row.headimgurl" height="50" />
          </td>
          <td class="is-center">
            {{ scope.row.nickname }}
          </td>
          <td class="is-center">
            {{ scope.row.sex | formatSex }}
          </td>
        </template>
      </mu-data-table>
    </mu-paper>
    <mu-flex class="pagination-filter" justify-content="center">
      <mu-pagination raised :total="pageData.count" :page-size="pageData.limit" :current.sync="pageData.page" @change="fetchData" />
    </mu-flex>
  </mu-container>
</template>

<script>
import { getMemberList, synchronizeMember } from '@/api/wx'

export default {
  filters: {
    formatStatus(val) {
      return (val === 1) ? '已关注' : '未关注'
    },
    formatSex(val) {
      const sex = {
        0: '未知',
        1: '男',
        2: '女'
      }
      return sex[val] || val
    }
  },
  data() {
    return {
      columns: [
        { title: '状态', name: 'subscribe', align: 'center' },
        { title: '头像', name: 'headimgurl', align: 'center' },
        { title: '昵称', name: 'nickname', align: 'center' },
        { title: '性别', name: 'sex', align: 'center' }
      ],
      list: [],
      pageData: {
        limit: 10,
        count: 0,
        page: 1
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const queryData = {
        page: this.pageData.page
      }
      getMemberList(queryData).then(response => {
        this.list = response.data
      })
    },
    synchronize() {
      synchronizeMember().then(() => {
        this.$toast.success('同步成功！')
        this.fetchData()
      })
    }
  }
}
</script>

<style scoped>

</style>
