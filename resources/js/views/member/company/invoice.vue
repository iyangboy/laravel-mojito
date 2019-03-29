<template>
    <div>
        <el-row>
            <el-col :span="6">
                账户余额：{{balanceMoney}}
            </el-col>
            <el-col :span="4">
                <el-button type="primary" size="mini" @click="dialogAddAccountForm = true" icon="el-icon-plus">
                    充值发票
                </el-button>
            </el-col>
            <el-col :span="12"></el-col>
        </el-row>
        <h3>发票充值列表</h3>
        <el-row v-if="false">
            <el-col>
                <el-form :inline="true" :model="queryParams" size="mini">
                    <el-form-item :label="$t('name')">
                        <el-input v-model="queryParams.name"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('email')">
                        <el-input v-model="queryParams.email"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="requestData" icon="el-icon-search">{{ $t('search') }}</el-button>
                        <el-button type="primary" @click="dialogAddAccountForm = true" icon="el-icon-plus">
                            {{ $t('add') }}
                        </el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="24">
                <el-table :data="tableListData" v-loading="loading" border stripe class="init_table">
                    <el-table-column
                        prop="create"
                        label="日期">
                    </el-table-column>
                    <el-table-column
                        prop=""
                        label="订单号">
                    </el-table-column>
                    <el-table-column
                        prop=""
                        label="充值金额">
                    </el-table-column>
                    <el-table-column
                        prop=""
                        label="备注">
                    </el-table-column>
                </el-table>
            </el-col>
        </el-row>
        <el-row>
            <el-col>

            </el-col>
        </el-row>
    </div>
</template>
<script>
    import ElRow from "../../../../../public/js/admin";
    export default {
        name: 'CompanyInvoiceIndex',
        components: {ElCol, ElRow},
        data() {
            return {
                balanceMoney: 0,
                queryParams:{},
                tableListData: [

                ],
            }
        },
        methods: {}
    }
</script>
