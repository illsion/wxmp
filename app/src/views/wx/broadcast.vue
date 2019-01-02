<template>
  <mu-container>
    <mu-paper :z-depth="1" class="app-paper">
      <mu-row class="action-filter">
        <mu-col span="12">
          <mu-button color="info" small @click="openDialog({})">
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
          <td class="is-center">
            {{ scope.row.type | formatType }}
          </td>
          <td class="is-center">
            {{ scope.row.status }}
          </td>
          <td class="is-center">
            <mu-button flat color="secondary" small @click="openDialog(scope.row)">
              编辑
            </mu-button>
            <mu-button flat small @click="deleteItem(scope.row.id)">
              删除
            </mu-button>
            <mu-button flat color="primary" small @click="send(scope.row.id)">
              发布
            </mu-button>
          </td>
        </template>
      </mu-data-table>
    </mu-paper>
    <mu-flex class="pagination-filter" justify-content="center">
      <mu-pagination raised :total="pageData.count" :page-size="pageData.limit" :current.sync="pageData.page" @change="fetchData" />
    </mu-flex>
    <update-broadcast :open.sync="update.open" :edit-data="update.data" @after-action="fetchData" />
  </mu-container>
</template>

<script>
import check from './check'
import UpdateBroadcast from './components/UpdateBroadcast'
import { getBroadcastList, deleteBroadcast, sendBroadcast } from '@/api/wx'
import { broadcastType } from './components/formData'

export default {
  components: {
    UpdateBroadcast
  },
  filters: {
    formatType(val) {
      return broadcastType[val] || val
    }
  },
  data() {
    return {
      columns: [
        { title: '标题', name: 'title', align: 'center' },
        { title: '类型', name: 'type', align: 'center' },
        { title: '状态', name: 'status', align: 'center' },
        { title: '操作', align: 'center', width: 500 }
      ],
      list: [],
      pageData: {
        count: 0,
        limit: 10,
        page: 1
      },
      update: {
        open: false,
        data: {}
      }
    }
  },
  created() {
    check()
    this.fetchData()
  },
  methods: {
    fetchData() {
      const queryData = {
        page: this.pageData.page
      }
      getBroadcastList(queryData).then(response => {
        this.list = response.items
        this.pageData = response.pageData
      })
    },
    openDialog(data = {}) {
      this.update.data = data
      this.update.open = true
    },
    deleteItem(id) {
      this.$confirm('确定要删除？', '提示', {
        type: 'warning'
      }).then(({ result }) => {
        if (result) {
          deleteBroadcast({ id: id }).then(response => {
            this.fetchData()
          })
        }
      })
    },
    send(id) {
      this.$confirm('确定要发布？', '提示', {
        type: 'warning'
      }).then(({ result }) => {
        if (result) {
          sendBroadcast({ id: id }).then(response => {
            this.fetchData()
          })
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
