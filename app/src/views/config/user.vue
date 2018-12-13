<template>
  <div>
    <mu-container>
      <mu-paper :z-depth="1" class="app-paper">
        <mu-row class="action-filter">
          <mu-col span="12">
            <mu-button color="success" small @click="openDialog({})">
              添加用户
            </mu-button>
          </mu-col>
        </mu-row>
        <mu-divider />
        <mu-data-table stripe :columns="columns" :data="list">
          <template slot-scope="scope">
            <td class="is-center">
              {{ scope.row.username }}
            </td>
            <td class="is-center">
              {{ scope.row.statusText }}
            </td>
            <td class="is-center">
              <mu-button flat color="secondary" small @click="openDialog(scope.row)">
                编辑
              </mu-button>
            </td>
          </template>
        </mu-data-table>
      </mu-paper>
      <mu-flex class="pagination-filter" justify-content="center">
        <mu-pagination raised :total="pageData.count" :page-size="pageData.limit" :current.sync="pageData.page" @change="fetchData" />
      </mu-flex>
    </mu-container>
    <mu-dialog :esc-press-close="false" :overlay-close="false" :title="validateForm.id ? '编辑' : '添加'" width="360" scrollable :open.sync="open">
      <mu-form ref="form" :model="validateForm">
        <mu-form-item label-float label="用户名" prop="username" :rules="usernameRules">
          <mu-text-field v-model="validateForm.username" prop="username" />
        </mu-form-item>
        <mu-form-item label-float label="密码" prop="password" :rules="passwordRules">
          <mu-text-field v-model="validateForm.password" type="password" prop="password" />
        </mu-form-item>
      </mu-form>
      <mu-button slot="actions" v-loading="loading" flat color="primary" :disabled="loading" @click="updateForm">
        确定
      </mu-button>
      <mu-button slot="actions" flat @click="closeDialog">
        取消
      </mu-button>
    </mu-dialog>
  </div>
</template>

<script>
import { getUserList, updateUser } from '@/api/config'

const defaultForm = {
  id: null,
  username: '',
  password: ''
}
export default {
  name: 'User',
  data() {
    return {
      validateForm: Object.assign({}, defaultForm),
      pageData: {
        limit: 10,
        count: 0,
        page: 1
      },
      columns: [
        { title: '用户名', name: 'username', align: 'center' },
        { title: '账号状态', name: 'statusText', align: 'center' },
        { title: '操作', align: 'center' }
      ],
      list: [],
      usernameRules: [
        { validate: (val) => !!val, message: '必须填写用户名' },
        { validate: (val) => val.length >= 3, message: '用户名长度大于3' }
      ],
      passwordRules: [
        { validate: (val) => !!val, message: '必须填写密码' },
        { validate: (val) => val.length >= 3 && val.length <= 10, message: '密码长度大于3小于10' }
      ],
      open: false,
      loading: false
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
      getUserList(queryData).then(response => {
        this.list = response.items
        this.pageData = response.pageData
      })
    },
    closeDialog() {
      this.open = false
    },
    openDialog(item) {
      this.open = true
      if (Object.keys(item).length === 0) {
        this.validateForm = Object.assign({}, defaultForm)
      } else {
        this.validateForm = Object.assign({}, item)
      }
    },
    createData() {
      this.open = true
    },
    updateForm() {
      this.$refs.form.validate().then((result) => {
        if (result) {
          this.loading = true
          updateUser(this.validateForm).then(response => {
            this.loading = false
            this.closeDialog()
            this.fetchData()
          }).catch(() => {
            this.loading = false
          })
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
