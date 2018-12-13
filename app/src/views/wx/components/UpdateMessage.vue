<template>
  <mu-dialog full-width :esc-press-close="false" :overlay-close="false" :title="validateForm.id ? '编辑' : '添加'" width="800" scrollable :open.sync="open">
    <div>
      <mu-tabs inverse :value.sync="validateForm.type" color="secondary" text-color="rgba(0, 0, 0, .54)">
        <mu-tab v-for="(option, index) in typeIndex" :key="index" :value="index">
          {{ option }}
        </mu-tab>
      </mu-tabs>
      <div>
        <mu-form ref="form" :model="validateForm">
          <mu-form-item label-float label="关键字" prop="keywords" :rules="rules.keywords">
            <mu-text-field v-model="validateForm.keywords" prop="keywords" />
          </mu-form-item>
          <mu-form-item label-float label="状态" prop="status" :rules="rules.status">
            <mu-select v-model.number="validateForm.status" :full-width="false">
              <mu-option v-for="(option,index) in statusIndex" :key="index" :label="option" :value="parseInt(index)" />
            </mu-select>
          </mu-form-item>
          <mu-form-item label="标题" prop="mp_message.title" :rules="rules.msg_title">
            <mu-text-field v-model="validateForm.mp_message.title" prop="mp_message.title" />
          </mu-form-item>
          <mu-form-item v-show="fieldsRule.description" label="描述">
            <mu-text-field v-model="validateForm.mp_message.description" />
          </mu-form-item>
          <mu-form-item v-show="fieldsRule.url" label="链接(http://)">
            <mu-text-field v-model="validateForm.mp_message.url" />
          </mu-form-item>
          <mu-form-item v-show="fieldsRule.content" label="内容">
            <mu-text-field v-model="validateForm.mp_message.content" multi-line :rows="3" />
          </mu-form-item>
        </mu-form>
        <upload v-show="fieldsRule.media_url" v-model="validateForm.mp_message.media_url" :img="validateForm.mp_message.full_media_url" />
      </div>
    </div>

    <mu-button slot="actions" v-loading="loading" flat color="primary" :disabled="loading" @click="updateForm">
      确定
    </mu-button>
    <mu-button slot="actions" flat @click="closeDialog">
      取消
    </mu-button>
  </mu-dialog>
</template>

<script>
import Upload from '@/components/UploadImage'
import { updateRule, getRuleItem } from '@/api/wx'
import { messageForm as defaultForm, fieldsForm } from './messageForm'

export default {
  name: 'UpdateMessage',
  components: {
    Upload
  },
  props: {
    open: {
      type: Boolean,
      default: false
    },
    editId: {
      type: Number,
      default: null
    },
    typeIndex: {
      type: Object,
      required: true
    },
    statusIndex: {
      type: Object,
      required: true
    },
    fields: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
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
      loading: false,
      validateForm: Object.assign({}, defaultForm)
    }
  },
  computed: {
    fieldsRule() {
      if (this.fields[this.validateForm.type]) {
        return Object.assign({}, fieldsForm, this.fields[this.validateForm.type])
      } else {
        return Object.assign({}, fieldsForm)
      }
    }
  },
  watch: {
    editId(newVal) {
      this.fetchData(newVal)
    }
  },
  methods: {
    fetchData(id) {
      if (id) {
        this.loading = true
        getRuleItem({
          id: id
        }).then(response => {
          this.validateForm = Object.assign({}, response)
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
      } else {
        this.validateForm = Object.assign({}, defaultForm)
      }
    },
    closeDialog() {
      this.loading = false
      this.$emit('update:open', false)
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
    }
  }
}
</script>

<style scoped>

</style>
