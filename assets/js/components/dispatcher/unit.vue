<template lang="html">
    <div style="width:100%;">
        <div class="bg-dark row p-2 d-flex align-items-center"><button class="btn btn-info mr-3"><i class="fas fa-angle-double-left"></i>&nbsp;Back</button><h4 class=''>S186</h4>
            <div class='ml-auto'>
              <div class="btn btn-success"><i class="fas fa-envelope"></i>&nbsp;Message</div>
              <div class="btn btn-primary"><i class="fas fa-edit"></i>&nbsp;Edit</div>
            </div>
          </div>
          <div class="row d-flex p-2">
            <div style='width:100%;'>
              <ul class='list-unstyled'>
                <li class='mb-1'>Callsign: S186</li>
                <li class='mb-1'>Status: <span class="state-code__box state-code__box--6">6 - On Scene</span></li>
                <li class='mb-1'>Crew: J.Anderson, B.Stevenson</li>
                <li class='mb-1'>Division: Resource Management Unit</li>
              </ul>
              <hr>
              <h4 class='pb-3'>Incidents attended</h4>
              <table class="table table-dark table-striped table-bordered">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Assigned</th>
                    <th scope="col">Cleared</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class='bg-success'>
                    <td scope="row">#296</td>
                    <td>Dockyard fire&nbsp;<span data-v-34bebf54="" class="badge badge-danger"> I Grade</span></td>
                    <td>2019-07-10 19:00:00</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td scope="row">234</td>
                    <td>RTC - Southbound Senora Freeway&nbsp;<span data-v-34bebf54="" class="badge badge-danger"> I Grade</span></td>
                    <td>2019-07-01 10:00:00</td>
                    <td>2019-07-01 11:30:00</td>
                  </tr>
                            <tr>
                    <td scope="row">230</td>
                    <td>64 M chest pain&nbsp;<span data-v-34bebf54="" class="badge badge-danger"> I Grade</span></td>
                    <td>2019-07-01 09:00:00</td>
                    <td>2019-07-01 09:40:00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
    </div>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
</style>
