package controller

import (
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
	"github.com/rickyananda1/golang_gin_gorm_JWT/dto"
	"github.com/rickyananda1/golang_gin_gorm_JWT/entity"
	"github.com/rickyananda1/golang_gin_gorm_JWT/helper"
	"github.com/rickyananda1/golang_gin_gorm_JWT/service"
)

type ProdukController interface {
	All(context *gin.Context)
	FindByID(context *gin.Context)
	Insert(context *gin.Context)
	Update(context *gin.Context)
	Delete(context *gin.Context)
}

type produkController struct {
	produkService service.ProdukService
	jwtService    service.JWTService
}

func NewProdukController(produkServ service.ProdukService, jwtServ service.JWTService) ProdukController {
	return &produkController{
		produkService: produkServ,
		jwtService:    jwtServ,
	}
}

func (c *produkController) All(context *gin.Context) {
	var produks []entity.Produk = c.produkService.All()
	res := helper.BuildResponse(true, "OK", produks)
	context.JSON(http.StatusOK, res)
}

func (c *produkController) FindByID(context *gin.Context) {
	id, err := strconv.ParseUint(context.Param("id"), 0, 0)
	if err != nil {
		res := helper.BuildErrorResponse("No param id was found", err.Error(), helper.EmptyObj{})
		context.AbortWithStatusJSON(http.StatusBadRequest, res)
	}
	var produk entity.Produk = c.produkService.FIndById(id)
	if (produk == entity.Produk{}) {
		res := helper.BuildErrorResponse("Data not found", "No Data with given id", helper.EmptyObj{})
		context.JSON(http.StatusNotFound, res)
	} else {
		res := helper.BuildResponse(true, "OK", produk)
		context.JSON(http.StatusOK, res)
	}
}

func (c *produkController) Insert(context *gin.Context) {
	var produkCreateDTO dto.ProdukCreateDTO
	errDTO := context.ShouldBind(&produkCreateDTO)
	if errDTO != nil {
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{})
		context.JSON(http.StatusBadRequest, res)
	} else {
		result := c.produkService.Insert(produkCreateDTO)
		response := helper.BuildResponse(true, "OK", result)
		context.JSON(http.StatusOK, response)
	}
}

func (c *produkController) Update(context *gin.Context) {
	var book entity.Produk
	var produkUpdateDTO dto.ProdukUpdateDTO
	errDTO := context.ShouldBind(&produkUpdateDTO)
	id, err := strconv.ParseUint(context.Param("id"), 0, 0)
	book.ID = id
	if errDTO != nil {
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{})
		context.JSON(http.StatusBadRequest, res)
	}

	if err != nil {
		response := helper.BuildErrorResponse("Failed to get id", "No param id were found", helper.EmptyObj{})
		context.JSON(http.StatusBadRequest, response)
	}

	if produkUpdateDTO.ID == book.ID {
		result := c.produkService.Update(produkUpdateDTO)
		response := helper.BuildResponse(true, "OK", result)
		context.JSON(http.StatusOK, response)
	} else {
		response := helper.BuildErrorResponse("You dont have permission", "You are not the owner", helper.EmptyObj{})
		context.JSON(http.StatusForbidden, response)
	}

}

func (c *produkController) Delete(context *gin.Context) {
	var produk entity.Produk
	id, err := strconv.ParseUint(context.Param("id"), 0, 0)
	if err != nil {
		response := helper.BuildErrorResponse("Failed to get id", "No param id were found", helper.EmptyObj{})
		context.JSON(http.StatusBadRequest, response)
	}
	produk.ID = id
	if produk.ID == id {
		c.produkService.Delete(produk)
		res := helper.BuildResponse(true, "Delete", helper.EmptyObj{})
		context.JSON(http.StatusOK, res)
	} else {
		response := helper.BuildErrorResponse("You dont have permission", "You are not the owner", helper.EmptyObj{})
		context.JSON(http.StatusForbidden, response)
	}
}
