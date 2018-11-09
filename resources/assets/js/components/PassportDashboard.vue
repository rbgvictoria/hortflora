<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-default">
              <div class="panel-heading">Dashboard</div>
              <div class="panel-body">
                  <passport-clients></passport-clients>
                  <passport-authorized-clients></passport-authorized-clients>
                  <passport-personal-access-tokens></passport-personal-access-tokens>
              </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
import PassportAuthorizedClients from './passport/AuthorizedClients'
import PassportClients from './passport/Clients'
import PassportPersonalAccessTokens from './passport/PersonalAccessTokens'

export default {
  components: {
    PassportClients,
    PassportAuthorizedClients,
    PassportPersonalAccessTokens
  }
}
</script>
