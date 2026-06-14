// Dashboard JavaScript for Auto Return Module

class AutoReturnDashboard {
    constructor() {
      this.loadData()
      // Auto refresh every 5 minutes
      setInterval(() => this.loadData(), 300000)
    }
  
    async loadData() {
      try {
        await Promise.all([this.loadStats(), this.loadExpiringItems()])
      } catch (error) {
        console.error("Error loading dashboard data:", error)
        this.showError("Failed to load dashboard data")
      }
    }
  
    async loadStats() {
      try {
        const response = await fetch("api-endpoints.php?action=getStats")
        const data = await response.json()
  
        if (data.success) {
          this.updateStatsDisplay(data.stats)
          this.updateStatsTable(data.stats)
        } else {
          throw new Error(data.error || "Failed to load stats")
        }
      } catch (error) {
        console.error("Error loading stats:", error)
        document.getElementById("statsBody").innerHTML = '<tr><td colspan="3">Error loading statistics</td></tr>'
      }
    }
  
    async loadExpiringItems() {
      try {
        const response = await fetch("api-endpoints.php?action=getExpiringItems")
        const data = await response.json()
  
        if (data.success) {
          this.updateExpiringItemsTable(data.items)
          this.updateExpiringItemsAlert(data.items.length)
        } else {
          throw new Error(data.error || "Failed to load expiring items")
        }
      } catch (error) {
        console.error("Error loading expiring items:", error)
        document.getElementById("expiringItemsBody").innerHTML =
          '<tr><td colspan="6">Error loading expiring items</td></tr>'
      }
    }
  
    updateStatsDisplay(stats) {
      let totalReturns = 0
      let totalQuantity = 0
  
      stats.forEach((stat) => {
        totalReturns += Number.parseInt(stat.total_auto_returns)
        totalQuantity += Number.parseInt(stat.total_quantity_returned)
      })
  
      document.getElementById("totalAutoReturns").textContent = totalReturns
      document.getElementById("totalQuantityReturned").textContent = totalQuantity
    }
  
    updateStatsTable(stats) {
      const tbody = document.getElementById("statsBody")
  
      if (stats.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3">No auto returns in the last 30 days</td></tr>'
        return
      }
  
      tbody.innerHTML = stats
        .map(
          (stat) => `
              <tr>
                  <td>${this.formatDate(stat.return_date)}</td>
                  <td>${stat.total_auto_returns}</td>
                  <td>${stat.total_quantity_returned}</td>
              </tr>
          `,
        )
        .join("")
    }
  
    updateExpiringItemsTable(items) {
      const tbody = document.getElementById("expiringItemsBody")
      document.getElementById("itemsAboutToExpire").textContent = items.length
  
      if (items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6">No items about to expire</td></tr>'
        return
      }
  
      tbody.innerHTML = items
        .map((item) => {
          const timeRemaining = 24 - Number.parseInt(item.hours_elapsed)
          return `
                  <tr>
                      <td>${item.item_id}</td>
                      <td>${item.item_name}</td>
                      <td>${item.quantity_released}</td>
                      <td>${this.formatDateTime(item.release_date)}</td>
                      <td>${item.hours_elapsed}h</td>
                      <td>${timeRemaining}h remaining</td>
                  </tr>
              `
        })
        .join("")
    }
  
    updateExpiringItemsAlert(count) {
      const alert = document.getElementById("expiringItemsAlert")
      if (count > 0) {
        alert.style.display = "block"
      } else {
        alert.style.display = "none"
      }
    }
  
    async runAutoReturn() {
      try {
        const button = event.target
        button.disabled = true
        button.textContent = "Processing..."
  
        const response = await fetch("auto-return-module.php", {
          method: "POST",
        })
        const result = await response.json()
  
        if (result.success) {
          alert(`Success: ${result.message}`)
          this.loadData() // Refresh data
        } else {
          alert(`Error: ${result.error}`)
        }
      } catch (error) {
        console.error("Error running auto return:", error)
        alert("Failed to run auto return process")
      } finally {
        const button = event.target
        button.disabled = false
        button.textContent = "Run Auto Return Now"
      }
    }
  
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString()
    }
  
    formatDateTime(dateString) {
      return new Date(dateString).toLocaleString()
    }
  
    showError(message) {
      alert(`Error: ${message}`)
    }
  }
  
  // Initialize dashboard when page loads
  document.addEventListener("DOMContentLoaded", () => {
    window.dashboard = new AutoReturnDashboard()
  })
  
  // Global functions for button clicks
  function runAutoReturn() {
    window.dashboard.runAutoReturn()
  }
  
  function loadData() {
    window.dashboard.loadData()
  }
  