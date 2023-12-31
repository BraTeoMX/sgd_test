USE [intimark_sia]
GO
/****** Object:  Table [dbo].[tbl_calendario]    Script Date: 2/2/2023 08:40:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tbl_calendario](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[fecha_calendario] [date] NULL,
	[id_modulo] [int] NULL,
	[estatus_fecha] [int] NULL,
	[detalle] [varchar](100) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET IDENTITY_INSERT [dbo].[tbl_calendario] ON 

INSERT [dbo].[tbl_calendario] ([id], [fecha_calendario], [id_modulo], [estatus_fecha], [detalle], [created_at], [updated_at], [deleted_at]) VALUES (1, CAST(N'2023-04-11' AS Date), 1, 1, N'JUEVES SANTO', NULL, NULL, NULL)
INSERT [dbo].[tbl_calendario] ([id], [fecha_calendario], [id_modulo], [estatus_fecha], [detalle], [created_at], [updated_at], [deleted_at]) VALUES (2, CAST(N'2023-04-12' AS Date), 1, 1, N'VIERNES SANTO', NULL, NULL, NULL)
INSERT [dbo].[tbl_calendario] ([id], [fecha_calendario], [id_modulo], [estatus_fecha], [detalle], [created_at], [updated_at], [deleted_at]) VALUES (10, CAST(N'2023-05-10' AS Date), 1, 1, N'Dia de las madres', CAST(N'2023-02-01T16:37:50.733' AS DateTime), CAST(N'2023-02-01T16:37:50.733' AS DateTime), NULL)
SET IDENTITY_INSERT [dbo].[tbl_calendario] OFF
