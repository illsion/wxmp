<template>
  <div>
    <mu-container>
      <mu-paper :z-depth="1" class="app-paper">
        <mu-row class="action-filter">
          <mu-col span="12">
            <mu-select v-model="queryData.type">
              <mu-option label="请选择类型" :value="null" />
              <mu-option v-for="(option,index) in typeIndex" :key="index" :label="option" :value="index" />
            </mu-select>
            <mu-button small color="info" @click="fetchData">
              搜索
            </mu-button>
            <mu-button color="success" small @click="openDialog({})">
              添加消息
            </mu-button>
          </mu-col>
        </mu-row>
        <mu-divider />
        <mu-data-table stripe :columns="columns" :data="list">
          <template slot-scope="scope">
            <td class="is-center">
              {{ scope.row.keywords }}
            </td>
            <td class="is-center">
              {{ scope.row.mp_message.title }}
            </td>
            <td class="is-center">
              {{ scope.row.typeText }}
            </td>
            <td class="is-center">
              {{ scope.row.statusText }}
            </td>
            <td class="is-center">
              <mu-button flat color="secondary" small @click="openDialog(scope.row)">
                编辑
              </mu-button>
              <mu-button flat color="success" small @click="showInfo(scope.row)">
                查看
              </mu-button>
              <mu-button flat small @click="deleteItem(scope.row.id)">
                删除
              </mu-button>
            </td>
          </template>
        </mu-data-table>
      </mu-paper>
      <mu-flex class="pagination-filter" justify-content="center">
        <mu-pagination raised :total="pageData.count" :page-size="pageData.limit" :current.sync="pageData.page" @change="fetchData" />
      </mu-flex>
    </mu-container>
    <mu-dialog :esc-press-close="false" :overlay-close="false" :title="validateForm.id ? '编辑' : '添加'" width="800" scrollable :open.sync="open">
      <mu-form ref="form" :model="validateForm">
        <mu-form-item label-float label="关键字" prop="keywords" :rules="rules.keywords">
          <mu-text-field v-model="validateForm.keywords" prop="keywords" />
        </mu-form-item>
        <mu-form-item label-float label="类型" prop="type" :rules="rules.type">
          <mu-select v-model="validateForm.type" :full-width="false">
            <mu-option v-for="(option,index) in typeIndex" :key="index" :label="option" :value="index" />
          </mu-select>
        </mu-form-item>
        <mu-form-item label-float label="状态" prop="status" :rules="rules.status">
          <mu-select v-model.number="validateForm.status" :full-width="false">
            <mu-option v-for="(option,index) in statusIndex" :key="index" :label="option" :value="parseInt(index)" />
          </mu-select>
        </mu-form-item>
        <mu-form-item label="标题" prop="mp_message.title" :rules="rules.msg_title">
          <mu-text-field v-model="validateForm.mp_message.title" prop="mp_message.title" />
        </mu-form-item>
        <mu-form-item label="描述">
          <mu-text-field v-model="validateForm.mp_message.description" />
        </mu-form-item>
        <mu-form-item label="链接(http://)">
          <mu-text-field v-model="validateForm.mp_message.url" />
        </mu-form-item>
        <mu-form-item label="内容">
          <mu-text-field v-model="validateForm.mp_message.content" multi-line :rows="3" />
        </mu-form-item>
      </mu-form>
      <upload v-model="validateForm.mp_message.media_url" :img="validateForm.mp_message.full_media_url" />
      <mu-button slot="actions" v-loading="loading" flat color="primary" :disabled="loading" @click="updateForm">
        确定
      </mu-button>
      <mu-button slot="actions" flat @click="closeDialog">
        取消
      </mu-button>
    </mu-dialog>
    <mu-dialog title="查看" width="800" scrollable :open.sync="info.open">
      <mu-list textline="two-line">
        <mu-list-item button>
          <mu-list-item-content>
            <mu-list-item-title>关键字</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.keywords }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
        <mu-list-item button>
          <mu-list-item-content>
            <mu-list-item-title>类型</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.typeText }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
        <mu-list-item button>
          <mu-list-item-content>
            <mu-list-item-title>状态</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.statusText }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
        <mu-list-item button>
          <mu-list-item-content>
            <mu-list-item-title>标题</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.mp_message.title }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
        <mu-list-item button>
          <mu-list-item-content>
            <mu-list-item-title>描述</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.mp_message.description }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
        <mu-list-item button>
          <mu-list-item-content>
            <mu-list-item-title>链接</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.mp_message.url }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
      </mu-list>
      <div class="app-list">
        <div class="title">
          内容
        </div>
        <div class="mu-item-sub-title">
          {{ info.item.mp_message.content }}
        </div>
      </div>
      <div class="app-list">
        <div class="title">
          图片
        </div>
        <img :src="info.item.mp_message.full_media_url" width="80" height="80">
      </div>
      <mu-button slot="actions" flat @click="info.open = false">
        关闭
      </mu-button>
    </mu-dialog>
  </div>
</template>

<script>
import { getRuleList, updateRule, deleteRule } from '@/api/wx'
import Upload from '@/components/UploadImage'

const defaultForm = {
  id: null,
  keywords: null,
  type: null,
  status: 1,
  mp_message: {
    id: null,
    title: null,
    description: null,
    content: null,
    url: null,
    media_url: null,
    full_media_url: null
  }
}
export default {
  components: {
    Upload
  },
  data() {
    return {
      validateForm: Object.assign({}, defaultForm),
      queryData: {
        type: null
      },
      typeIndex: [],
      statusIndex: [],
      pageData: {
        limit: 10,
        count: 0,
        page: 1
      },
      columns: [
        { title: '关键字', name: 'username', align: 'center' },
        { title: '标题', name: 'mp_message.title', align: 'center' },
        { title: '类型', name: 'typeText', align: 'center' },
        { title: '状态', name: 'statusText', align: 'center' },
        { title: '操作', align: 'center', width: 500 }
      ],
      list: [],
      rules: {
        keywords: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        type: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        status: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        msg_title: [
          { validate: (val) => !!val, message: '不能为空' }
        ]
      },
      open: false,
      loading: false,
      info: {
        open: false,
        item: Object.assign({}, defaultForm)
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const queryData = {
        page: this.pageData.page,
        ...this.queryData
      }
      getRuleList(queryData).then(response => {
        this.list = response.items
        this.typeIndex = response.typeIndex
        this.statusIndex = response.statusIndex
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
          updateRule(this.validateForm).then(response => {
            this.loading = false
            this.closeDialog()
            this.fetchData()
          }).catch(() => {
            this.loading = false
          })
        }
      })
    },
    deleteItem(id) {
      this.$confirm('确定要删除？', '提示', {
        type: 'warning'
      }).then(({ result }) => {
        if (result) {
          deleteRule({ id: id }).then(response => {
            this.fetchData()
          })
        }
      })
    },
    showInfo(item) {
      this.info.open = true
      this.info.item = Object.assign({}, item)
    }
  }
}
</script>

<style scoped>
    .app-list {
        padding-right: 16px;
        padding-left: 16px;
        color: rgba(0,0,0,.87);
        margin-bottom: 20px;
    }
</style>
