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

	bookRoutes := r.Group("api/produks")
	{
		bookRoutes.GET("/", produkController.All)
		bookRoutes.POST("/", produkController.Insert)
		bookRoutes.GET("/:id", produkController.FindByID)
		bookRoutes.PUT("/:id", produkController.Update)
		bookRoutes.DELETE("/:id", produkController.Delete)
	}

	r.Run()
}
