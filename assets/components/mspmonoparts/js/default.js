/* eslint no-undef: */

class MSPMP {
  constructor () {
    this._count = 0
    this.selectBlockId = 'mspMonoParts_count'
    this.selectCountName = 'mspMonoParts_count_select'
    this.printCountId = 'mspMonoParts_count_print'
    this.printMonthlyId = 'mspMonoParts_count_monthly_print'
    this.toggleSelect = (display = 'block') => {
      const selectElements = document.getElementById(this.selectBlockId)
      selectElements.style.display = display
    }
    this.showSelect = () => this.toggleSelect('block')
    this.hideSelect = () => this.toggleSelect('none')
    this.ms2PaymentFieldCallback = (response) => {
      // console.log(response)
      if ('payment' in response.data) {
        console.log(mspmpConfig.payments, parseInt(response.data.payment), response.data.payment, mspmpConfig.payments.indexOf(parseInt(response.data.payment)))
        if (mspmpConfig.payments.indexOf(parseInt(response.data.payment)) !== -1) this.showSelect()
        else this.hideSelect()
      }
    }
    this.ms2PaymentGetCostCallback = (response) => {
      const cost = response.data.cost
      const monthly = Math.floor(cost / this.count)
      const printCount = document.getElementById(this.printCountId)
      const printMonthly = document.getElementById(this.printMonthlyId)
      if (printCount) printCount.innerHTML = String(this.count)
      if (printMonthly) printMonthly.innerHTML = String(miniShop2.Utils.formatPrice(monthly))
    }
  }

  get count () {
    if (this._count === 0) this._count = mspmpConfig.count
    return this._count
  }

  set count (count) {
    this._count = count
  }
}

const mspmp = new MSPMP()

miniShop2.Callbacks.add('Order.add.response.success', 'mspmpPaymentCheck', mspmp.ms2PaymentFieldCallback)
miniShop2.Callbacks.add('Order.getcost.response.success', 'mspmpPaymentGetCost', mspmp.ms2PaymentGetCostCallback)
if (mspmpConfig.show) {
  mspmp.showSelect()
}

/** @type {function} */
$(document).on('change', '#' + mspmp.selectCountName, function (e) {
  mspmp.count = parseInt($(e.target).val())
  miniShop2.Order.getcost()
})
