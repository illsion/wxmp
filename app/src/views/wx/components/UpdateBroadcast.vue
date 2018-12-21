<template>
  <mu-dialog full-width :esc-press-close="false" :overlay-close="false" :title="validateForm.id ? '编辑' : '添加'" width="800" scrollable :open.sync="open">
    <div>
      <mu-tabs inverse :value.sync="validateForm.type" color="secondary" text-color="rgba(0, 0, 0, .54)" @change="changeTab">
        <mu-tab v-for="(option, index) in typeIndex" :key="index" :value="parseInt(index)">
          {{ option }}
        </mu-tab>
      </mu-tabs>
      <div v-if="validateForm.type === 1">
        <mu-form ref="form" :model="validateForm">
          <mu-form-item label-float label="内容" prop="title" :rules="rules.title">
            <mu-text-field v-model="validateForm.title" prop="title" />
          </mu-form-item>
        </mu-form>
      </div>
      <div v-else-if="validateForm.type === 2">
        <mu-form ref="form" :model="validateForm">
          <mu-form-item label-float label="标题" prop="title" :rules="rules.title">
            <mu-text-field v-model="validateForm.title" prop="title" />
          </mu-form-item>
          <mu-form-item label-float label="作者" prop="author">
            <mu-text-field v-model="validateForm.author" />
          </mu-form-item>
          <mu-form-item label-float label="摘要" prop="digest" :rules="rules.digest">
            <mu-text-field v-model="validateForm.digest" prop="digest" />
          </mu-form-item>
          <mu-form-item label-float label="原文链接" prop="content_source_url">
            <mu-text-field v-model="validateForm.content_source_url" />
          </mu-form-item>
        </mu-form>
        <edit v-model="validateForm.content" />
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
import Edit from '@/components/Tinymce'

const defaultForm = {
  id: null,
  title: '',
  type: 1,
  content: '',
  author: '',
  content_source_url: '',
  digest: '',
  thumb_media_id: ''
}

export default {
  name: 'UpdateBroadcast',
  components: {
    Edit
  },
  props: {
    open: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      validateForm: Object.assign({}, defaultForm),
      textForm: Object.assign({}, defaultForm, { type: 1 }),
      imgForm: Object.assign({}, defaultForm, { type: 2 }),
      loading: false,
      typeIndex: {
        1: '文本',
        2: '单图文'
      },
      rules: {
        title: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        digest: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        thumb_media_id: [
          { validate: (val) => !!val, message: '不能为空' }
        ]
      }
    }
  },
  methods: {
    closeDialog() {
      this.$emit('update:open', false)
    },
    updateForm() {
      console.log(this.validateForm)
    },
    changeTab(val) {
      if (val === 1) {
        // 文本
        this.imgForm = Object.assign({}, this.validateForm, { type: 2 })
        this.validateForm = Object.assign({}, this.textForm, { type: val })
      } else if (val === 2) {
        this.textForm = Object.assign({}, this.validateForm, { type: 1 })
        this.validateForm = Object.assign({}, this.imgForm, { type: val })
      }
    }
  }
}
</script>

<style scoped>

</style>
