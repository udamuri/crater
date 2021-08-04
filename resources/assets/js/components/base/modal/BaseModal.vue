<template>
  <div>
    <sw-modal ref="baseModal" :variant="variant">
      <template v-slot:header>
        <div
          class="absolute flex content-center justify-center w-5 cursor-pointer"
          style="top: 20px; right: 15px"
          @click="closeModal"
        >
          <x-icon />
        </div>
        <span>{{ modalTitle }}</span>
      </template>
      <component :is="component" />
    </sw-modal>
  </div>
</template>

<script>
import { XIcon } from '@vue-hero-icons/solid'
import { mapActions, mapGetters } from 'vuex'
import CustomerModal from './CustomerModal'
import BackupModal from './BackupModal'
import MailTestModal from './MailTestModal'
import FileDiskModal from './FileDiskModal'
import SetDefaultDiskModal from './SetDefaultDiskModal'
import CustomFieldModal from './CustomField/Index'

export default {
  components: {
    CustomerModal,
    BackupModal,
    MailTestModal,
    XIcon,
    FileDiskModal,
    SetDefaultDiskModal,
    CustomFieldModal,
  },
  data() {
    return {
      component: '',
    }
  },
  computed: {
    ...mapGetters('modal', [
      'modalActive',
      'modalTitle',
      'componentName',
      'modalSize',
      'modalData',
      'variant',
    ]),
  },
  watch: {
    componentName(component) {
      if (!component) {
        return
      }
      this.component = component
    },
    modalActive(status) {
      if (status) {
        this.$refs.baseModal.open()
        return true
      }
      this.$refs.baseModal.close()
      return false
    },
  },
  methods: {
    ...mapActions('modal', ['openModal', 'closeModal']),
  },
}
</script>
