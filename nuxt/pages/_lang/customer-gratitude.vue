<template>
  <div>
    <section class="section section-components mt-3">
      <div class="container shape-container d-flex align-items-center">
        <div class="col px-0">
          <div class="row justify-content-center align-items-center">
            <div class="col-lg-12 text-center pt-lg">
              <img :src="blueImage" class="img-fluid blue-banner">
              <p class="lead mt-4 mb-5">
                <slot>
                  {{ $t('customer_gratitude.title') }}
                </slot>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section section-components">
      <div class="container">
        <div class="alert alert-primary mb-4" role="alert">
          <strong>{{ $t('customer_gratitude.list_title') }}:</strong>
        </div>
        <div v-for="item in items" :key="item.id" class="row mb-5">
          <div class="col-md-12 mb-5 mb-md-0">
            <div class="card card-lift--hover shadow border-0">
              <nuxt-link to="/">
                <img v-lazy="appUrl + item.link_image" class="card-img">
              </nuxt-link>
            </div>
            <div>
              <h1 class="text-center">{{ item.name }}</h1>
              <h2 class="text-center">{{ item.address }}</h2>
              <b class="">{{ $t('customer_gratitude.number') }}: <span class="text-success">{{ item.min_people }} - {{ item.max_people }} </span> {{ $t('customer_gratitude.people') }}</b>
              <br/>
              <b v-if="item.status.name === 'free'" class="">{{ $t('customer_gratitude.status') }}: <span class="text-success"> {{ $t('customer_gratitude.free') }} </span></b>
              <b v-else class="">{{ $t('customer_gratitude.status') }}: <span class="text-warning"> {{ $t('customer_gratitude.booking') }} </span></b>
            </div>
          </div>
        </div>
        <nav aria-label="Page navigation example">
          <paginate
            :page-count="lastPage"
            :click-handler="clickCallback"
            :prev-text="'prev'"
            :next-text="'next'"
            :container-class="'pagination justify-content-end'"
            :page-class="'page-item'"
            :page-link-class="'page-link'"
            :prev-class="'page-item'"
            :prev-link-class="'page-link'"
            :next-class="'page-item'"
            :next-link-class="'page-link'">
          </paginate>
        </nav>
      </div>
    </section>
  </div>
</template>
<script>
import Paginate from '~/components/argon-demo/Paginate'
import axios from '~/plugins/axios'

export default {
  name: 'customer-gratitude',
  layout: 'other',
  components: {
    Paginate
  },
  data() {
    return {
      blueImage: process.env.NUXT_BANNER_BLUE_IMG,
      appUrl: process.env.APP_URL,
      items: [],
      lastPage: 0
    }
  },
  computed: {
  },
  watch: {
  },
  created() {
    this.clickCallback(1)
  },
  methods: {
    clickCallback(page) {
      axios.get('customer-gratitude?page=' + page)
        .then(response => {
          this.lastPage = response.data.result.last_page
          this.items = response.data.result.data
        })
    }
  }
}
</script>
<style>
</style>
