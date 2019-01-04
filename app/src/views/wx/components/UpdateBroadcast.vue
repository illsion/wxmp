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
        <upload-image :accept="'image/jpeg'" :img="validateForm.thumb_media_path_full" :text="'封面图片'" :send-media="'media_temp'" @after-upload="setMediaId" />
        <edit v-model="validateForm.content" :accept="'image/png,image/jpeg'" :send-media="'article'" />
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
import UploadImage from '@/components/Upload/UploadImage'
import { updateBroadcast } from '@/api/wx'
import { broadcastType } from './formData'

const defaultForm = {
  id: null,
  list_id: null,
  title: '',
  type: 1,
  content: '',
  author: '',
  content_source_url: '',
  digest: '',
  thumb_media_id: '',
  thumb_media_path: '',
  thumb_media_path_full: ''
}

export default {
  name: 'UpdateBroadcast',
  components: {
    Edit,
    UploadImage
  },
  props: {
    open: {
      type: Boolean,
      default: false
    },
    editData: {
      type: Object,
      default() {
        return {}
      }
    }
  },
  data() {
    return {
      validateForm: Object.assign({}, defaultForm),
      textForm: Object.assign({}, defaultForm, { type: 1 }),
      imgForm: Object.assign({}, defaultForm, { type: 2 }),
      loading: false,
      typeIndex: broadcastType,
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
  watch: {
    editData(newVal) {
      if (Object.keys(newVal).length > 0) {
        newVal = {
          id: newVal.id,
          title: newVal.title,
          type: newVal.type,
          list_id: newVal.mp_news_lists[0].id,
          author: newVal.mp_news_lists[0].author,
          content_source_url: newVal.mp_news_lists[0].content_source_url,
          digest: newVal.mp_news_lists[0].digest,
          content: newVal.mp_news_lists[0].content,
          thumb_media_id: newVal.mp_news_lists[0].thumb_media_id,
          thumb_media_path: newVal.mp_news_lists[0].thumb_media_path,
          thumb_media_path_full: newVal.mp_news_lists[0].thumb_media_path_full
        }
      }
      this.validateForm = Object.assign({}, defaultForm, newVal)
    }
  },
  methods: {
    closeDialog() {
      this.$emit('update:open', false)
    },
    updateForm() {
      this.$refs.form.validate().then((result) => {
        if (result) {
          this.loading = true
          const data = Object.assign({}, this.validateForm)
          const update = {
            id: data.id,
            title: data.title,
            type: data.type,
            mp_news_lists: [
              {
                id: data.list_id,
                title: data.title,
                author: data.author,
                content_source_url: data.content_source_url,
                digest: data.digest,
                content: (data.type === 1) ? '' : data.content,
                thumb_media_id: data.thumb_media_id,
                thumb_media_path: data.thumb_media_path
              }
            ]
          }
          updateBroadcast(update).then(response => {
            this.closeDialog()
            this.$emit('after-action')
            this.loading = false
          }).catch(() => {
            this.loading = false
          })
        }
      })
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
    },
    setMediaId(val) {
      this.validateForm.thumb_media_path = val.path
      this.validateForm.thumb_media_id = val.media_id
    }
  }
}
</script>

<style scoped>

</style>

