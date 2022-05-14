package main

import (
	"github.com/gin-gonic/gin"
	"github.com/rickyananda1/golang_gin_gorm_JWT/config"
	"github.com/rickyananda1/golang_gin_gorm_JWT/controller"
	"github.com/rickyananda1/golang_gin_gorm_JWT/repository"
	"github.com/rickyananda1/golang_gin_gorm_JWT/service"

	"gorm.io/gorm"
)

var (
	db              *gorm.DB                   = config.SetupDatabaseConnection()
	orderRepository repository.OrderRepository = repository.NewOrderRepository(db)
	jwtService      service.JWTService         = service.NewJWTService()
	orderService    service.OrderService       = service.NewOrderService(orderRepository)
	orderController controller.OrderController = controller.NewOrderController(orderService, jwtService)
)

func main() {
	defer config.CloseDatabaseConnection(db)
	r := gin.Default()

	bookRoutes := r.Group("api/orders")
	{
		bookRoutes.GET("/", orderController.All)
		bookRoutes.POST("/", orderController.Insert)
		bookRoutes.GET("/:id", orderController.FindByID)
		bookRoutes.PUT("/:id", orderController.Update)
		bookRoutes.DELETE("/:id", orderController.Delete)
	}
	err := r.Run("127.0.0.1:8003")
	if err != nil {
		return
	}
	r.Run()
}
