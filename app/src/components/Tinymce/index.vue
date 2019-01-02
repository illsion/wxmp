<template>
  <div :class="{ fullscreen: fullscreen }" class="tinymce-container">
    <textarea :id="id" />
    <div class="upload-btn">
      <edit-image @after-upload="afterUpload" />
    </div>
  </div>
</template>

<script>
import EditImage from './components/EditImage'
import plugins from './plugin'
import toolbar from './toolbar'

export default {
  name: 'Tinymce',
  components: {
    EditImage
  },
  props: {
    id: {
      type: String,
      default: function() {
        return 'tinymce-' + +new Date() + ((Math.random() * 1000).toFixed(0) + '')
      }
    },
    value: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      fullscreen: false,
      hasChange: false,
      hasInit: false
    }
  },
  watch: {
    value(val) {
      if (!this.hasChange && this.hasInit) {
        this.$nextTick(() =>
          window.tinymce.get(this.id).setContent(val || ''))
      }
    }
  },
  mounted() {
    this.init()
  },
  deactivated() {
    this.destroy()
  },
  destroyed() {
    this.destroy()
  },
  methods: {
    init() {
      const _this = this
      window.tinymce.init({
        language: 'zh_CN',
        selector: `#${this.id}`,
        plugins: plugins,
        toolbar: toolbar,
        setup(ed) {
          ed.on('FullscreenStateChanged', (e) => {
            _this.fullscreen = e.state
          })
        },
        init_instance_callback: editor => {
          if (_this.value) {
            editor.setContent(_this.value)
          }
          _this.hasInit = true
          editor.on('NodeChange Change KeyUp SetContent', () => {
            _this.hasChange = true
            _this.$emit('input', editor.getContent())
          })
        }
      })
    },
    destroy() {
      const tinymce = window.tinymce.get(this.id)
      if (this.fullscreen) {
        tinymce.execCommand('mceFullScreen')
      }
      if (tinymce) {
        tinymce.destroy()
      }
    },
    afterUpload(img) {
      window.tinymce.get(this.id).insertContent(`<img src="${img.fullPath}" >`)
    },
    setContent(value) {
      window.tinymce.get(this.id).setContent(value)
    },
    getContent() {
      window.tinymce.get(this.id).getContent()
    }
  }
}
</script>

<style scoped>
  .tinymce-container {
    position: relative;
  }
  .upload-btn {
    position: absolute;
    right: 4px;
    top: 4px;
    z-index: 20161223;
  }

  .fullscreen .upload-btn {
    z-index: 20161223;
    position: fixed;
  }
</style>
