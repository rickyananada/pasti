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
	db               *gorm.DB                    = config.SetupDatabaseConnection()
	produkRepository repository.ProdukRepository = repository.NewProdukRepository(db)
	jwtService       service.JWTService          = service.NewJWTService()
	produkService    service.ProdukService       = service.NewProdukService(produkRepository)
	produkController controller.ProdukController = controller.NewProdukController(produkService, jwtService)
)

func main() {
	defer config.CloseDatabaseConnection(db)
	r := gin.Default()

	productRoutes := r.Group("api/produks")
	{
		productRoutes.GET("/", produkController.All)
		productRoutes.POST("/", produkController.Insert)
		productRoutes.GET("/:id", produkController.FindByID)
		productRoutes.PATCH("/:id", produkController.Update)
		productRoutes.DELETE("/:id", produkController.Delete)
	}
	err := r.Run("127.0.0.1:8001")
	if err != nil {
		return
	}
	r.Run()
}
